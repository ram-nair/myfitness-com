<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\BaseController;
use App\Http\Resources\StoreProductResource;
use App\Order;
use App\OrderStatus;
use App\OrderItem;
use App\OrderPayment;
use App\Traits\PaymentTraits;
use App\Traits\NotificationTraits;
use App\UserPaymentMethod;
use Auth;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use URL;
use Yajra\Datatables\Datatables;

class OrderController extends BaseController
{
    use NotificationTraits, PaymentTraits;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $this->authorize(Order::class, 'order');
        $this->setPageTitle('Orders', 'Orders List');
        return view('admin.orders.index');
    }

    public function datatable()
    {
        $currentUser = Auth::guard('admin')->user();
        $orders = Order::where('store_id',1)->with('user')->select('*')->orderBy('created_at', 'desc');
        return Datatables::of($orders)
            ->rawColumns(['actions'])
            ->editColumn('payment_status', function ($order) {
                return Helper::getPaymentStatus($order->payment_status);
            })
            ->editColumn('order_status', function ($order) {
                return trans("api.order_status.ECOM.{$order->order_status}");
            })
            ->editColumn('payment_type', function ($order) {
                return Helper::getPaymentType($order->payment_type);
            })
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($orders) use ($currentUser) {
                $b = '';
                $b .= '<a href="' . URL::route('store.orders.show', $orders->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $b;
            })->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $store = $order->store;
        $user = $order->user;
        $address = $order->address;
        $products = $order->orderItem();
        $stockProducts = $order->orderItem()->get();
        $outOfStockProducts = $order->orderItem()->onlyTrashed()->get();
        // dd($outOfStockProducts);
        $this->setPageTitle('Order Details', 'Order Details');
        return view('admin.orders.show', compact('order', 'store', 'user', 'address', 'stockProducts', 'outOfStockProducts'));
    }

    public function detailsDatatable()
    {
        return "hai";
    }

    public function updateStatus(Request $request, Order $order)
    {
        $cancelDuration = (int) config('settings.order_cancel_duration');
        $updatedTime = Carbon::parse($order->created_at);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($updatedTime);
        if ($totalDuration < $cancelDuration) {
            alert()->error("Order substitution time is not over", "Can't Update");
            return redirect()->route('store.orders.show', $order->id);
        }
        $prevStatus = $order->order_status;
        $order->order_status = $request->order_status;
        $order->save();
        $order->orderStatusHistory()->save(new OrderStatus(['status' =>  $request->order_status]));
        if ($request->order_status === "out_for_delivery" && $order->payment_type === "credit_card") {
            //Payment Process
            $paymentSuccess = $this->processPayment($order);
            if (isset($paymentSuccess['error'])) {
                $order->order_status = $prevStatus;
                $order->payment_status = 3;
                $order->save();
                $order->orderStatusHistory()->save(new OrderStatus(['status' =>  $prevStatus]));
                alert()->error($paymentSuccess['message'], $paymentSuccess['title']);
                return redirect()->route('store.orders.show', $order->id);
            }
            if ($paymentSuccess) {
                $order->payment_status = 1;
                $order->save();
            }
        }
        $this->createOrderStatusPush($order->user, 300, $order, "ECOM");
        alert()->success('Order status updated', 'Updated');
        return redirect()->route('store.orders.show', $order->id);
    }

    public function updateItems(Request $request, Order $order)
    {
        $orderItemsId = explode(",", $request->order_item_ids);
        OrderItem::whereIn('id', $orderItemsId)->delete();
        $order = calculateECommerceOrderSum($order);
        $outOfStockItems = OrderItem::where('order_id', $order->id)->onlyTrashed()->get();
        foreach ($outOfStockItems as $outOfStockItem) {
            $storeProductItems[] = new StoreProductResource($outOfStockItem->storeProduct);
        }
        $this->orderItemOutOfStockPush($order->user, 303, $order, $storeProductItems, "ECOM");
        alert()->success('Product Details Updated', 'Updated');
        return redirect()->route('store.orders.show', $order->id);
    }

    public function processPayment($order)
    {
        if ($order->payment_status == 1) {
            return ['error' => true, 'message' => 'Payment already processed', 'title' => "Payment Processed"];
        }
        $cardDetails = UserPaymentMethod::find($order->card_id);
        if (empty($cardDetails)) {
            return ['error' => true, 'message' => 'Card not found in records', 'title' => 'Card Error'];
        }
        if ($cardDetails->status == 2) {
            return ['error' => true, 'message' => 'Card is disabled by user', 'title' => 'Card Disabled'];
        }
        if ($cardDetails->user_id != $order->user_id) {
            return ['error' => true, 'message' => 'Card does not belong to this user', 'title' => 'Card Error'];
        }
        $amount = $order->total_amount;
        if (App::environment(['local', 'staging'])) {
            $amount = (int)floor($order->total_amount);
        }
        $payment = $this->chargeCustomer($cardDetails->si_reference, "ECOM" . $order->order_id, $amount);
        if ($payment === false) {
            return ['error' => true, 'message' => 'Payment could not be processed', 'title' => 'Payment Failed'];
        }
        if (!empty($payment)) {
            $data = [
                'si_charge_status' => $payment['si_charge_status'],
                'si_charge_txn_status' => $payment['si_charge_txn_status'],
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $amount,
                'reference_no' => $payment['reference_no'],
                'si_sub_ref_no' => $payment['si_sub_ref_no'],
                'receipt_no' => $payment['receipt_no'],
                'si_error_desc' => $payment['si_error_desc'],
                'full_response' => $payment,
            ];
            // dd($data);
            OrderPayment::create($data);
            //transaction success
            if ($payment['si_charge_status'] == 0 && $payment['si_charge_txn_status'] == 0) {
                return true;
            } else {
                return ['error' => true, 'message' => 'Payment could not be processed', 'title' => 'Payment Failed'];
            }
        }
        return ['error' => true, 'message' => 'Unknown Error in Payment', 'title' => 'Payment Failed'];
    }
}
