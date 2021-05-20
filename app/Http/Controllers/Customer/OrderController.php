<?php

namespace App\Http\Controllers\Customer;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderPlainCollection;
use App\Http\Resources\StoreProductPlainCollection;
use App\Order;
use App\OrderItem;
use App\OrderStatus;
use App\Product;
use App\Store;
use App\StoreDaysSlot;
use App\StoreProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Order $order, Request $request)
    // {
    //     $order->delete();
    //     return successResponse(trans('api.order.cart_deleted'), $this->cartContents($request));
    // }

    public function createOrder(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'address_id' => 'required|exists:App\UserAddress,id',
            'payment_method' => 'required', //alias payment_type
            'order_type' => 'required',
            'card_id' => 'required_if:payment_method,==,credit_card', //alias payment_type
            'slot_id' => 'required_if:order_type,==,Scheduled',
        ]);
        $total = 0;
        $final_total = 0;
        $vatAmount = 0;
        $vat = config('settings.vat');
        $order_id = generateOrderNumber();
        $store = getStore($request->store_id);
        $user = $request->user();
        $payment_method = $request->payment_method; //alias payment_type
        $slot_id = $request->slot_id ?? "";
        $order_type = $request->order_type;
        $note = $request->notes ?? "";
        $scheduled_notes = $request->scheduled_notes ?? "";
        $delivery_address = "";
        $address = $user->address()->where('id', $request->address_id)->first();
        if ($slot_id) {
            $count = Order::where('slot_id', $slot_id)->count();
            $slots = StoreDaysSlot::where('id', $slot_id)->first();
            if ($slots->capacity <= $count) {
                return errorResponse(trans('api.order.no_slot'));
            }
        }
        if (!empty($address)) {
            $delivery_address = $address->apartment . '<br>' . $address->building_name . '<br>' . $address->address_name . '<br>' . $address->location;
        }
        $contents = Cart::where('user_id', $request->user()->id)
            ->where('store_id', $request->store_id)
            ->get()->pluck('quantity', 'product_id');
        $productIds = $contents->keys();
        $storeProducts = StoreProduct::whereIn('id', $productIds)->get();
        $orders = $outstock = [];
        foreach ($storeProducts as $item) {
            if ($contents->keyBy($item->id)->first() <= $item->stock && !$item->out_of_stock) {
                $orders[] = $item;
            } else {
                $outstock[] = $item;
            }
        }
        if (!empty($outstock)) {
            return response()->json([
                'statusCode' => 200,
                'message' => trans('api.order.outofstock'),
                'data' => [
                    "out_of_stock" => new StoreProductPlainCollection($outstock),
                ],
            ]);
        }
        if (!empty($orders)) {
            $totalAmount = 0;
            $order = new Order();
            $order->order_id = $order_id;
            $order->store_id = $request->store_id;
            $order->user_id = $request->user()->id;
            $order->address_id = $request->address_id;
            $order->delivery_address = $delivery_address;
            $order->vat_amount = 0;
            $order->amount_exclusive_vat = 0;
            $order->total_amount = 0;
            $order->vat_percentage = $vat;
            $order->service_charge = $store->service_charge;
            $order->payment_type = $payment_method;
            $order->card_id = $request->card_id ?? null;
            $order->order_type = $order_type;
            $order->slot_id = $slot_id;
            $order->scheduled_notes = $scheduled_notes;
            $order->notes = $note;
            $order->save();
            $cartContents = $contents->toArray();
            foreach ($orders as $orderItem) {
                $requestedQty = $cartContents[$orderItem->id];
                $totalAmount += $requestedQty * $orderItem->unit_price;
                $productTotal = $requestedQty * $orderItem->unit_price;
                $lineItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $orderItem->id,
                    'product_name' => $orderItem->product->name,
                    'product_price' => $orderItem->unit_price,
                    'quantity' => $requestedQty,
                    'total_amount' => $productTotal,
                ]);
                $orderItem->decrement('stock', $requestedQty);
            }
            $vatAmount = ($totalAmount + $store->service_charge) * ((float) $vat / 100);
            $orderTotal = $totalAmount + $vatAmount + $store->service_charge;
            $order->vat_amount = $vatAmount;
            $order->amount_exclusive_vat = $totalAmount;
            $order->total_amount = $orderTotal;
            $order->save();
            $order->orderStatusHistory()->save(new OrderStatus(['status' => 'submitted']));
            $order = Order::with(['orderItem', 'store'])->where('id', $order->id)->get();
            // Cart::where('user_id', $request->user()->id)->where('store_id', $request->store_id)->delete();
            return response()->json([
                'statusCode' => 200,
                'message' => trans('api.order.list'),
                'data' => [
                    "order_data" => new OrderPlainCollection($order),
                    "out_of_stock" => new StoreProductPlainCollection($outstock),
                ],
            ]);
        } else {
            return errorResponse(trans('api.order.outofstock'), ['out_of_stock' => new StoreProductPlainCollection($outstock)]);
        }
    }

    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric',
        ]);
        if ($order->order_status !== "submitted") {
            return errorResponse(trans('api.order.already_processed'));
        }
        if ($request->quantity < 0) {
            return errorResponse(trans('api.order.invalid_quantity'));
        }
        $cancelDuration = config('settings.order_cancel_duration');
        $updatedTime = Carbon::parse($order->time_of_item_update);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($updatedTime);
        if ($totalDuration > $cancelDuration) {
            return errorResponse(trans('api.order.timeout'));
        }
        $orderProductCheck = OrderItem::where("order_id", $order->id)->where('product_id', $request->product_id)->withTrashed()->first();
        // dd($orderProductCheck);
        if ($orderProductCheck) {
            if ($orderProductCheck->storeProduct->stock < $request->quantity + $orderProductCheck->quantity || $orderProductCheck->storeProduct->out_of_stock) {
                return errorResponse(trans('api.order.outofstock'));
            }
        }
        // $product = Product::where('id', $request->product_id)->first();
        $storeProduct = StoreProduct::where('id', $request->product_id)->first();
        $productTotal = $request->quantity * $storeProduct->unit_price;
        if ($storeProduct->stock < $request->quantity || $storeProduct->out_of_stock) {
            return errorResponse(trans('api.order.outofstock'));
        }
        if ($request->quantity == 0) {
            $orderProductCheck->forceDelete();
        } else {
            if ($orderProductCheck) {
                $orderProductCheck->quantity = $request->quantity;
                $orderProductCheck->total_amount = $productTotal;
                if (!is_null($orderProductCheck->deleted_at)) {
                    $orderProductCheck->deleted_at = null;
                }
                $orderProductCheck->save();
            } else {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $storeProduct->id,
                    'product_name' => $storeProduct->product->name,
                    'product_price' => $storeProduct->unit_price,
                    'quantity' => $request->quantity,
                    'total_amount' => $productTotal,
                ]);
            }
        }
        $order = calculateECommerceOrderSum($order);
        $storeProduct->decrement('stock', $request->quantity);
        $order = Order::with(['orderItem', 'store'])->where('id', $order->id)->get();
        return new OrderCollection($order);
    }
    public function orderDetails(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        $order = Order::with(['orderItem', 'store'])->where('id', $request->order_id)->get();
        return new OrderCollection($order);
    }

    public function myorderHistory(Request $request)
    {
        $filter = $request->order_type;
        $myorder = Order::with(['orderItem', 'store'])->where('user_id', $request->user()->id)->orderBy('created_at', 'desc');
        if ($filter) {
            $myorder->where('order_type', $filter);
        }
        if (!empty($myorder)) {
            return new OrderCollection($myorder->paginate($request->limit ?? 20));
        } else {
            return errorResponse(trans('api.order.no_data'));
        }
    }
    public function addressCheck(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'address_id' => 'required',
        ]);
        $distance = config('settings.distance_radius');
        $store_id = $request->store_id;
        $address = $request->user()->address()->where('id', $request->address_id)->first();
        if (!empty($address)) {
            //[$address->latitude, $address->longitude, $address->latitude]
            $store_count = Store::selectRaw("stores.id, name,image, location, store_timing, start_at, end_at, credit_card, cash_accept, description, service_type, email, mobile, business_type_id, business_type_category_id
        , bring_card, min_order_amount, latitude, longitude, featured, speed, accuracy, location_type, male, female, any, my_location, in_store, on_my_location_charge, time_to_deliver")
                ->join('store_boundary', 'stores.id', '=', 'store_boundary.store_id')
                ->whereRaw("ST_Contains(positions, GeomFromText('POINT($address->latitude $address->longitude)'))")
                ->where('active', 1)
                ->where('stores.id', $store_id)->get();
            if (!$store_count->isEmpty()) {
                return successResponse(trans('api.success'), null);
            } else {
                return errorResponse(trans('api.order.location_notnear'));
            }
        } else {
            return errorResponse(trans('api.order.no_address'));
        }
    }

    public function orderCancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $order = Order::with('store')->where('id', $request->order_id)->first();
        $cancelDuration = (int) config('settings.order_cancel_duration');
        $updatedTime = Carbon::parse($order->created_at);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($updatedTime);
        if (($order->order_status == 'submitted') && ($totalDuration < $cancelDuration)) {
            $order->order_status = 'cancelled';
            $order->save();
            $order->orderStatusHistory()->save(new OrderStatus(['status' => 'cancelled']));
            $order = Order::with(['orderItem', 'store'])->where('id', $request->order_id)->get();
            return new OrderCollection($order);
        } else {
            return errorResponse(trans('api.order.timeout'));
        }
    }

    public function slotCheck(Request $request)
    {
        $request->validate([
            'slot_id' => 'required',
        ]);
        $slot_id = $request->slot_id;
        $count = Order::where('slot_id', $slot_id)->count();
        $slots = StoreDaysSlot::where('id', $slot_id)->first();
        if ($slots->capacity > $count) {
            return successResponse(trans('api.order.yes_slot'), ["free_capacity" => $slots->capacity - $count, "filled_capacity" => $count]);
        } else {
            return errorResponse(trans('api.order.no_slot'));
        }
    }
}
