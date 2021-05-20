<?php

namespace App\Http\Controllers\Services;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceOrderCollection;
use App\Http\Resources\ServiceOrderPlainCollection;
use App\Order;
use App\Product;
use App\ReportProblem;
use App\ServiceCart;
use App\ServiceOrder;
use App\ServiceOrderItem;
use App\ServiceOrderStatus;
use App\ServiceStoreSlot;
use App\Store;
use App\StoreDaysSlot;
use App\StoreServiceProducts;
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
        $request->validate([
            //'cart_id' => 'required',
            'store_id' => 'required',
            'address_id' => 'required|exists:App\UserAddress,id',
            'payment_method' => 'required',
            'pick_up_slot_id' => 'required|exists:App\StoreDaysSlot,id',
            'drop_off_slot_id' => 'nullable|exists:App\ServiceStoreSlot,id',
            'card_id' => 'required_if:payment_method,==,credit_card', //alias payment_type
        ]);
        $slot = StoreDaysSlot::find($request->pick_up_slot_id)->first();
        $pickUpSlotDate = null;
        $pickUpSlotName = null;
        if ($slot) {
            $count = ServiceOrder::where('pick_up_slot_id', $slot->id)->count();
            $slots = StoreDaysSlot::where('id', $slot->id)->first();
            if ($slots->capacity <= $count) {
                return errorResponse(trans('api.order.no_slot'));
            }
            $pickUpSlotName = $slot->slots->slot_name;
            $pickUpSlotDate = $slot->days;
        }
        $dropOffSlotName = null;
        if ($request->drop_off_slot_id) {
            $dropOffSlot = ServiceStoreSlot::find($request->drop_off_slot_id)->first();
            $dropOffSlotName = $dropOffSlot->slots->slot_name ?? null;
        }
        // dd($pickUpSlotDate . "--->" . $pickUpSlotName . "---->" . $dropOffSlotName);
        $vat = config('settings.vat');
        $order_id = generateOrderNumber();
        $user = $request->user();
        $payment_method = $request->payment_method;
        $address = $user->address()->where('id', $request->address_id)->first();
        $drop_address = $user->address()->where('id', $request->drop_address_id)->first();
        $drop_off_address = $delivery_address = "";
        $store = getStore($request->store_id);
        $service_type = $store->service_type;
        if (!empty($address)) {
            $delivery_address = $address->apartment . '<br>' .
                $address->building_name . '<br>' .
                $address->address_name . '<br>' .
                $address->location;
        }
        if (!empty($drop_address)) {
            $drop_off_address = $drop_address->apartment . '<br>' .
                $drop_address->building_name . '<br>' .
                $drop_address->address_name . '<br>' .
                $drop_address->location;
        }
        $contents = ServiceCart::where('user_id', $request->user()->id)
            ->where('store_id', $request->store_id)
            ->get()->toArray();
        // $orderItem = [];
        if (!empty($contents)) {
            $totalAmount = 0;
            $order = new ServiceOrder();
            $order->order_id = $order_id;
            $order->store_id = $request->store_id;
            $order->user_id = $request->user()->id;
            $order->address_id = $request->address_id;
            $order->delivery_address = $delivery_address;
            $order->drop_address_id = $request->drop_address_id;
            $order->drop_address = $drop_off_address;
            $order->service_type = $service_type;
            $order->vat_amount = 0;
            $order->amount_exclusive_vat = 0;
            $order->total_amount = 0;
            $order->service_charge = $store->service_charge;
            $order->vat_percentage = $vat;
            $order->payment_type = $payment_method;
            $order->card_id = $request->card_id ?? null;
            $order->pick_up_slot_id = $request->pick_up_slot_id;
            $order->pick_up_date = $pickUpSlotDate ?? null;
            $order->pick_up_slot = $pickUpSlotName;
            $order->drop_off_slot_id = $request->drop_off_slot_id;
            $order->drop_off_slot = $dropOffSlotName;
            // $order->scheduled_notes = $scheduled_notes;
            $order->notes = $request->notes ?? "";
            $order->save();
            foreach ($contents as $content) {
                $storeServiceProduct = StoreServiceProducts::where('id', $content['product_id'])->first();
                $totalAmount += $content['quantity'] * $storeServiceProduct->unit_price;
                $orderItemTotal = $content['quantity'] * $storeServiceProduct->unit_price;
                $orderItem = ServiceOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $storeServiceProduct->id,
                    'product_name' => $storeServiceProduct->product->name,
                    'product_price' => $storeServiceProduct->unit_price,
                    'quantity' => $content['quantity'],
                    'total_amount' => $orderItemTotal,
                ]);
            }
            $vatAmount = ($totalAmount + $store->service_charge) * ((float) $vat / 100);
            $orderTotal = $totalAmount + $vatAmount + $store->service_charge;
            $order->vat_amount = $vatAmount;
            $order->amount_exclusive_vat = $totalAmount;
            $order->total_amount = $orderTotal;
            $order->save();
            $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' => 'submitted']));
            $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order->id)->get();
            ServiceCart::where('user_id', $request->user()->id)->where('store_id', $request->store_id)->delete();

            return response()->json([
                'statusCode' => 200,
                'message' => trans('services.order.list'),
                'data' => [
                    "order_data" => new ServiceOrderPlainCollection($order),
                ],
            ]);
        } else {
            return errorResponse(trans('services.order.cart_empty'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    // public function destroy(ServiceOrder $order)
    // {
    //     $order->delete();
    //     return successResponse(trans('services.order.order_deleted'));
    // }

    public function updateOrder(Request $request, ServiceOrder $order)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($order->order_status !== "submitted") {
            return errorResponse(trans('services.order.already_processed'));
        }
        if ($request->quantity < 0) {
            return errorResponse(trans('services.order.invalid_quantity'));
        }
        $orderProductCheck = ServiceOrderItem::where("order_id", $order->id)->where('product_id', $request->product_id)->first();
        $storeProduct = StoreServiceProducts::where('id', $request->product_id)->first();
        $productTotal = $request->quantity * $storeProduct->unit_price;
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
                $orderItem = ServiceOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $storeProduct->id,
                    'product_name' => $storeProduct->product->name,
                    'product_price' => $storeProduct->unit_price,
                    'quantity' => $request->quantity,
                    'total_amount' => $productTotal,
                ]);
            }
        }
        $order = calculateServiceOrderSum($order);
        $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order->id)->get();
        return new ServiceOrderCollection($order);
    }

    public function orderDetails(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        return $this->orderContents($request->order_id);
    }

    private function orderContents($order_id)
    {
        $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order_id)->get();
        return new ServiceOrderCollection($order);
    }

    public function myOrderHistory(Request $request)
    {
        $serviceType = $request->service_type ?? false;
        if ($serviceType) {
            $myOrder = ServiceOrder::with(['orderItem', 'store'])->where('user_id', $request->user()->id)->orderBy('created_at', 'desc');
            $myOrder->where('service_type', $serviceType);
        }else{
            $myOrder = ServiceOrder::with(['orderItem', 'store'])->where('user_id', $request->user()->id)->orderBy('created_at', 'desc');
        }
        if (!empty($myOrder)) {
            return new ServiceOrderCollection($myOrder->paginate($request->limit ?? 20));
        } else {
            return errorResponse(trans('services.order.no_data'));
        }
    }

    public function addressCheck(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'address_id' => 'required|exists:App\UserAddress,id',
        ]);

        $distance = config('settings.distance_radius');
        $customer = $request->user();
        $store_id = $request->store_id;
        $address = $customer->address()->where('id', $request->address_id)->first();
        if (!empty($address)) {
            $store_count = Store::selectRaw("stores.id, name, image, location, store_timing, start_at, end_at, credit_card, cash_accept, description, service_type, email, mobile, business_type_id, business_type_category_id
        , bring_card, min_order_amount, latitude, longitude, featured, speed, accuracy, location_type, male, female, any, my_location, in_store, on_my_location_charge, time_to_deliver")
                ->join('store_boundary', 'stores.id', '=', 'store_boundary.store_id')
                ->whereRaw("ST_Contains(positions, GeomFromText('POINT($address->latitude $address->longitude)'))")
                ->where('active', 1)
                ->where('stores.id', $store_id)->get();
            // ->having("distance", "<", $distance)->get();
            if (!$store_count->isEmpty()) {
                return successResponse(trans('services.success'), null);
            } else {
                return errorResponse(trans('services.order.location_notnear'));
            }
        } else {
            return errorResponse(trans('services.order.no_address'));
        }
    }

    public function orderCancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        $order = ServiceOrder::where('id', $request->order_id)->first();
        $cancelDuration = (int) config('settings.order_cancel_duration');
        $updatedTime = Carbon::parse($order->created_at);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($updatedTime);
        if (($order->order_status === 'submitted') && ($totalDuration < $cancelDuration)) {
            $order->order_status = "cancelled";
            $order->save();
            $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' => 'cancelled']));
            return $this->orderContents($order->id);
        } else {
            return errorResponse(trans('services.order.timeout'));
        }
    }

    public function slotCheck(Request $request)
    {
        $request->validate([
            'slot_id' => 'required',
        ]);
        $slot_id = $request->slot_id;
        $count = ServiceOrder::where('pick_up_slot_id', $slot_id)->count();
        $slots = StoreDaysSlot::where('id', $slot_id)->first();
        if ($slots && $slots->capacity > $count) {
            return successResponse(trans('services.order.yes_slot'), ["free_capacity" => $slots->capacity - $count, "filled_capacity" => $count]);
        } else {
            return errorResponse(trans('services.order.no_slot'));
        }
    }

    //Service Type 2

    public function serviceType2Order(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'address_id' => 'required_if:service_place,==,my_location',
            'payment_method' => 'required',
            'service_schedule_slot_id' => 'required|exists:App\StoreDaysSlot,id',
            'service_place' => 'required',
            'card_id' => 'required_if:payment_method,==,credit_card', //alias payment_type
        ]);
        $slot = StoreDaysSlot::find($request->service_schedule_slot_id);
        $pickUpSlotDate = null;
        $pickUpSlotName = null;
        if ($slot) {
            $count = ServiceOrder::where('pick_up_slot_id', $slot->id)->count();
            $slots = StoreDaysSlot::where('id', $slot->id)->first();
            if ($slots->capacity <= $count) {
                return errorResponse(trans('api.order.no_slot'));
            }
            $pickUpSlotName = $slot->slots->slot_name;
            $pickUpSlotDate = $slot->days;
        }
        $total = $final_total = $vatAmount = 0;
        $vat = config('settings.vat');
        $store = getStore($request->store_id);
        $order_id = generateOrderNumber();
        $user = $request->user();
        $payment_method = $request->payment_method;
        $address = "";
        if ($request->address_id) {
            $address = $user->address()->where('id', $request->address_id)->first();
            $drop_address = $user->address()->where('id', $request->drop_address_id)->first();
        }
        $drop_off_address = $delivery_address = "";
        if (!empty($address)) {
            $delivery_address = $address->apartment . '<br>' .
                $address->building_name . '<br>' .
                $address->address_name . '<br>' .
                $address->location;
        }
        $store = Store::find($request->store_id);
        $service_type = $store->service_type;
        $onMyLocationCharge = 0;
        if ($request->service_place === "my_location") {
            $onMyLocationCharge = $store->on_my_location_charge;
        }
        $contents = ServiceCart::where('user_id', $request->user()->id)
            ->where('store_id', $request->store_id)
            ->get()->toArray();
        // $orderItem = [];
        if (!empty($contents)) {
            $totalAmount = 0;
            $order = new ServiceOrder();
            $order->order_id = $order_id;
            $order->store_id = $request->store_id;
            $order->user_id = $request->user()->id;
            $order->address_id = $request->address_id;
            $order->delivery_address = $delivery_address;
            $order->service_type = $service_type;
            $order->gender_preference = $request->gender_preference;
            $order->vat_amount = 0;
            $order->amount_exclusive_vat = 0;
            $order->total_amount = 0;
            $order->service_charge = $store->service_charge;
            $order->vat_percentage = $vat;
            $order->payment_type = $payment_method;
            $order->card_id = $request->card_id ?? null;
            $order->pick_up_slot_id = $request->service_schedule_slot_id;
            $order->pick_up_date = $pickUpSlotDate;
            $order->pick_up_slot = $pickUpSlotName;
            $order->location_type = $request->service_place;
            $order->on_my_location_charge = $onMyLocationCharge;
            $order->notes = $request->notes ?? "";
            $order->save();
            foreach ($contents as $content) {
                $storeServiceProduct = StoreServiceProducts::where('id', $content['product_id'])->first();
                $totalAmount += $content['quantity'] * $storeServiceProduct->unit_price;
                $orderItemTotal = $content['quantity'] * $storeServiceProduct->unit_price;
                $orderItem = ServiceOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $storeServiceProduct->id,
                    'product_name' => $storeServiceProduct->product->name,
                    'product_price' => $storeServiceProduct->unit_price,
                    'quantity' => $content['quantity'],
                    'total_amount' => $orderItemTotal,
                ]);
            }
            $totalAmount = $totalAmount + $store->service_charge ?? 0;
            $vatAmount = ($totalAmount + $onMyLocationCharge) * ((float) $vat / 100);
            $orderTotal = $totalAmount + $vatAmount + $onMyLocationCharge;
            $order->vat_amount = $vatAmount;
            $order->amount_exclusive_vat = $totalAmount;
            $order->total_amount = $orderTotal;
            $order->save();
            $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' => 'submitted']));
            $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order->id)->get();
            ServiceCart::where('user_id', $request->user()->id)->where('store_id', $request->store_id)->delete();
            return response()->json([
                'statusCode' => 200,
                'message' => trans('services.order.list'),
                'data' => [
                    "order_data" => new ServiceOrderPlainCollection($order),
                ],
            ]);
        } else {
            return errorResponse(trans('services.order.cart_empty'));
        }
    }

    public function updateServiceType2Order(Request $request, ServiceOrder $order)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($order->order_status !== "submitted") {
            return errorResponse(trans('services.order.already_processed'));
        }
        if ($request->quantity < 0) {
            return errorResponse(trans('services.order.invalid_quantity'));
        }
        $vat = config('settings.vat');
        $store = getStore($order->store_id);
        $oldVatAmount = $order->vat_amount;
        $oldAmountExclusiveAmount = $order->amount_exclusive_vat;
        $oldTotalAmount = $order->total_amount;
        $orderProductCheck = ServiceOrderItem::where("order_id", $order->id)->where('product_id', $request->product_id)->first();
        $storeProduct = StoreServiceProducts::where('id', $request->product_id)->first();
        $productTotal = $request->quantity * $storeProduct->unit_price;
        $vatAmount = $productTotal * ($vat / 100);
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
                $orderItem = ServiceOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $storeProduct->id,
                    'product_name' => $storeProduct->product->name,
                    'product_price' => $storeProduct->unit_price,
                    'quantity' => $request->quantity,
                    'total_amount' => $productTotal,
                ]);
            }
        }
        $order = calculateServiceOrderSum($order);
        $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order->id)->get();
        return new ServiceOrderCollection($order);
    }

    public function reportProblem(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'store_id' => 'required',
            'subject' => 'required',
            'details' => 'required',
        ]);

        $reportProblem = ReportProblem::create([
            'name' => $request->subject,
            'description' => $request->details,
            'order_id' => $request->order_id,
            'store_id' => $request->store_id,
            'user_id' => $request->user()->id,
        ]);
        if ($reportProblem) {
            return successResponse(trans('services.report.added'), null);
        } else {
            return errorResponse(trans('services.report.error'));
        }
    }

    public function rateOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'store_id' => 'required',
            'rating' => "integer|min:1|max:5|nullable",
            'accuracy' => "integer|min:1|max:5|nullable",
        ]);
        $store = getStore($request->store_id);
        if ($store->business_type_id == 1) {
            $order = Order::where('id', $request->order_id)->where('user_id', $request->user()->id)->first();
            if ($request->has('rating')) {
                $order->rating = $request->rating;
            }
            if ($request->has('accuracy')) {
                $order->accuracy = $request->accuracy;
            }
            $order->save();
        } else {
            $serviceOrder = ServiceOrder::where('id', $request->order_id)->where('user_id', $request->user()->id)->first();
            if ($request->has('rating')) {
                $serviceOrder->rating = $request->rating;
            }
            if ($request->has('accuracy')) {
                $serviceOrder->accuracy = $request->accuracy;
            }
            $serviceOrder->save();
        }
        return successResponse(trans('services.order.rated'));
    }

    //Service Type 3
    public function getPaymentInfo(Request $request)
    {

        $request->validate([
            'store_id' => 'required',
            'product_id' => 'required',
            'max_cleaner' => 'required',
            'max_hour' => 'required',
        ]);

        $vat = config('settings.vat');
        $store = getStore($request->store_id);
        $storeServiceProduct = StoreServiceProducts::where('id', $request->product_id)->first();
        if ($storeServiceProduct) {
            $is_bring_material_charge = $request->is_bring_material_charge;
            $max_value = $request->max_cleaner + $request->max_hour;
            $bring_material_charge = ($storeServiceProduct->product)?$storeServiceProduct->product->bring_material_charge:0;
            $totalAmount = $storeServiceProduct->unit_price * $max_value;
            $totalAmount = $totalAmount + $store->service_charge;
            $vatAmount = $totalAmount * ((float) $vat / 100);
            if($is_bring_material_charge == 1){
                $orderTotal = $totalAmount + $vatAmount + $bring_material_charge;
            }else{
                $orderTotal = $totalAmount + $vatAmount;
            }

            return response()->json([
                'statusCode' => 200,
                'message' => trans('services.order.payment_info'),
                'data' => [
                    "max_cleaner" => $request->max_cleaner,
                    "max_hour" => $request->max_hour,
                    "vat" => $vat,
                    "unit_price" => round_my_number($storeServiceProduct->unit_price),
                    "service_charge" => round_my_number($store->service_charge),
                    "total_amount" => round_my_number($totalAmount),
                    "vat_calculated_amount" => round_my_number($vatAmount),
                    "bring_material_charge" => round_my_number($bring_material_charge),
                    "order_total" => round_my_number($orderTotal),

                ],
            ]);
        } else {
            return errorResponse(trans('services.order.product_not_found'));
        }
    }
    public function serviceType3Order(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'product_id' => 'required',
            'address_id' => 'required',
            'payment_method' => 'required',
            'schedule_date' => 'required',
            'schedule_time' => 'required',
            'max_cleaner' => 'required',
            'max_hour' => 'required',
            'card_id' => 'required_if:payment_method,==,credit_card', //alias payment_type
        ]);

        $vat = config('settings.vat');
        $store = getStore($request->store_id);
        $order_id = generateOrderNumber();
        $user = $request->user();
        $payment_method = $request->payment_method;
        $is_bring_material_charge = isset($request->is_bring_material_charge)?$request->is_bring_material_charge:0;
        $address = "";
        if ($request->address_id) {
            $address = $user->address()->where('id', $request->address_id)->first();
        }
        $location_address = "";
        if (!empty($address)) {
            $location_address = $address->apartment . '<br>' .
                $address->building_name . '<br>' .
                $address->address_name . '<br>' .
                $address->location;
        }
        $service_type = $store->service_type;
        $onMyLocationCharge = 0;
        $storeServiceProduct = StoreServiceProducts::where('id', $request->product_id)->first();
        if ($storeServiceProduct) {
            $max_value = $request->max_cleaner + $request->max_hour;
            $totalAmount = $storeServiceProduct->unit_price * $max_value;
            $totalAmount = $totalAmount + $store->service_charge;
            $vatAmount = $totalAmount * ((float) $vat / 100);
            $bring_material_charge = ($storeServiceProduct->product)?$storeServiceProduct->product->bring_material_charge:0;
            if($is_bring_material_charge == 1){
                $orderTotal = $totalAmount + $vatAmount + $onMyLocationCharge + $bring_material_charge;
            }else{
                $orderTotal = $totalAmount + $vatAmount + $onMyLocationCharge;
            }

            $order = new ServiceOrder();
            $order->order_id = $order_id;
            $order->store_id = $request->store_id;
            $order->user_id = $request->user()->id;
            $order->address_id = $request->address_id;
            $order->delivery_address = $location_address;
            $order->service_location = $location_address;
            $order->service_type = $service_type;
            $order->gender_preference = $request->gender_preference;
            $order->max_cleaner = $request->max_cleaner;
            $order->max_hour = $request->max_hour;
            $order->cleaning_materials = $request->cleaning_materials;
            $order->bring_material_charge = ($is_bring_material_charge == 1)?$bring_material_charge:0;
            $order->schedule_date = date('Y-m-d', strtotime($request->schedule_date));
            $order->schedule_time = $request->schedule_time;
            $order->vat_amount = $vatAmount;
            $order->amount_exclusive_vat = $totalAmount;
            $order->total_amount = $orderTotal;
            $order->service_charge = $store->service_charge;
            $order->vat_percentage = $vat;
            $order->payment_type = $payment_method;
            $order->card_id = $request->card_id ?? null;
            $order->on_my_location_charge = $onMyLocationCharge;
            $order->notes = $request->notes ?? "";
            $order->save();

            ServiceOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $storeServiceProduct->id,
                'product_name' => $storeServiceProduct->product->name,
                'product_price' => $storeServiceProduct->unit_price,
                'quantity' => $max_value,
                'total_amount' => $storeServiceProduct->unit_price * $max_value,
            ]);

            $order->serviceOrderStatusHistory()->save(new ServiceOrderStatus(['status' => 'submitted']));
            $order = ServiceOrder::with(['orderItem', 'store'])->where('id', $order->id)->get();
            return response()->json([
                'statusCode' => 200,
                'message' => trans('services.order.list'),
                'data' => [
                    "order_data" => new ServiceOrderPlainCollection($order),
                ],
            ]);
        } else {
            return errorResponse(trans('services.order.product_not_found'));
        }
    }
}
