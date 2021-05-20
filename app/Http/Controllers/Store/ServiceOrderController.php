<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseController;
use App\ServiceOrder;
use App\ServiceOrderItem;
use App\ServiceOrderPayment;
use App\ServiceOrderStatus;
use App\Traits\NotificationTraits;
use App\UserPaymentMethod;
use Auth;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;
use App\Traits\PaymentTraits;
use Illuminate\Support\Facades\App;

class ServiceOrderController extends BaseController
{
    use NotificationTraits, PaymentTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $this->setPageTitle('Orders', 'Orders List');
        return view('services.orders.index');
    }

    public function datatable()
    {
        $currentUser = Auth::user();
        $orders = ServiceOrder::where('store_id', $currentUser->id)->with('user')
            ->where('service_type', $currentUser->service_type)->select('*')->orderBy('created_at', 'desc');
        return Datatables::of($orders)
            ->rawColumns(['actions'])
            ->editColumn('payment_status', function ($order) {
                return Helper::getPaymentStatus($order->payment_status);
            })
            ->editColumn('order_status', function ($order) {
                $type = $order->service_type === "service_type_1" ? "ST1" : "ST2";
                return trans("api.order_status.{$type}.{$order->order_status}");
            })
            ->editColumn('payment_type', function ($order) {
                return Helper::getPaymentType($order->payment_type);
            })
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($orders) use ($currentUser) {
                $b = '';
                $b .= '<a href="' . URL::route('store.service-orders.show', $orders->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $b;
            })->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceOrder $serviceOrder)
    {
        $store = $serviceOrder->store;
        $user = $serviceOrder->user;
        $address = $serviceOrder->address;
        $products = $serviceOrder->orderItem;
        $this->setPageTitle('Order Details', 'Order Details');
        return view('services.orders.show', compact('serviceOrder', 'store', 'user', 'address', 'products'));
    }

    public function updateStatus(Request $request, ServiceOrder $order)
    {
        $cancelDuration = (int) config('settings.order_cancel_duration');
        $updatedTime = Carbon::parse($order->created_at);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($updatedTime);
        if ($totalDuration < $cancelDuration) {
            alert()->error("Order substitution time is not over", "Can't Update");
            return redirect()->route('store.service-orders.show', $order->id);
        }
        $prevStatus = $order->order_status;
        $order->order_status = $request->order_status;
        $order->save();
        $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' =>  $request->order_status]));
        if ($request->order_status === "assigned" && $order->payment_type === "credit_card") {
            //Payment Process
            $paymentSuccess = $this->processPayment($order);
            if (isset($paymentSuccess['error'])) {
                $order->order_status = $prevStatus;
                $order->payment_status = 3;
                $order->save();
                $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' =>  $prevStatus]));
                alert()->error($paymentSuccess['message'], $paymentSuccess['title']);
                return redirect()->route('store.service-orders.show', $order->id);
            }
            if ($paymentSuccess) {
                $order->payment_status = 1;
                $order->save();
            }
        }
        $st1_code = 302;
        $st_type = "ST2";
        if ($order->service_type === "service_type_1") {
            $st1_code = 301;
            $st_type = "ST1";
        }
        $pushEvent = $this->createOrderStatusPush($order->user, $st1_code, $order, $st_type);
        alert()->success('Order status updated', 'Updated');
        return redirect()->route('store.service-orders.show', $order->id);
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
        $st_type = $order->service_type === "service_type_1" ? "ST1" : "ST2";
        $payment = $this->chargeCustomer($cardDetails->si_reference, $st_type . '' . $order->order_id, $amount);
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
            ServiceOrderPayment::create($data);
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
