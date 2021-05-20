<?php

namespace App\Http\Controllers\Customer;

use App\BusinessTypeCategory;
use App\Category;
use App\ClassOrder;
use App\ClassOrderPayment;
use App\ClassOrderSlot;
use App\ClassOrderStatus;
use App\ClassReportProblem;
use App\GeneralClass;
use App\GeneralClassAttendees;
use App\GeneralClassSlots;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ClassOrderCollection;
use App\Http\Resources\ClassOrderResource;
use App\Http\Resources\ClassSlotResource;
use App\Http\Resources\GeneralClassResource;
use App\Package;
use App\Traits\NotificationTraits;
use App\Traits\PaymentTraits;
use App\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class GeneralClassController extends Controller
{
    use NotificationTraits, PaymentTraits;

    public function index(Request $request)
    {
        $businessTypeCatIds = BusinessTypeCategory::where('business_type_id', 2)->pluck('id');
        $categories = Category::where('parent_cat_id', 0)
            ->whereIn('business_type_category_id', $businessTypeCatIds)->get();
        $query = GeneralClass::with('packages');
        if ($request->type) {
            $query->whereIn('type', $request->type);
        }
        if ($request->category_id) {
            $query->whereIn('category_id', $request->category_id);
        }
        $classes = $query->orderBy("created_at")->paginate($request->limit ?? 20);
        return response()->json([
            'statusCode' => 200,
            'message' => trans('api.classes.list'),
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'classes' => $classes->isEmpty() ? null : GeneralClassResource::collection($classes),
            ],
            "links" => $classes->isEmpty() ? null : [
                "first" => $classes->url(1),
                "last" => $classes->url($classes->lastPage()),
                "prev" => $classes->previousPageUrl(),
                "next" => $classes->nextPageUrl(),
            ],
            "meta" => $classes->isEmpty() ? null : [
                "current_page" => $classes->currentPage(),
                "from" => $classes->firstItem(),
                "last_page" => $classes->lastPage(),
                "path" => null,
                "per_page" => $classes->perPage(),
                "to" => $classes->lastItem(),
                "total" => $classes->total(),
            ],
        ]);
        // return new GeneralClassCollection($classes);
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
        ]);
        $class_id = $request->class_id;
        $date = $request->date;
        $today = date('Y-m-d');
        $newDate = Carbon::now()->addDay(3)->format('Y-m-d');
        $slots = GeneralClassSlots::where('class_id', $class_id)->where('status', 1);
        if (!empty($date)) {
            $slots->whereIn('slot_date', $date);
        } else {
            $slots->whereBetween('slot_date', [$today, $newDate]);
        }
        $slots->limit(50)->orderBy('slot_date');
        $slots_array = $slots->get()->groupBy(function ($item) {
            return $item->slot_date->format('Y-m-d');
        });
        $newSlots = null;
        foreach ($slots_array as $date => $item) {
            $newSlots[] = ['date' => $date, 'slots' => ClassSlotResource::collection($item)];
        }
        return response()->json([
            'statusCode' => 200,
            'message' => trans('api.classes.slots'),
            'data' => $newSlots,
        ]);
    }

    public function slotCheck(Request $request)
    {
        $request->validate([
            'slot_id' => 'required',
            // 'package_id' => 'required',
        ]);
        $slotIds = $request->slot_id;
        $slots = GeneralClassSlots::whereIn('id', $slotIds)->where('status', 1)->get();
        $slotsList = [];
        foreach ($slots as $slot) {
            $booked = ClassOrderSlot::where('slot_id', $slot->id)->count();
            if ($slot->capacity > $booked) {
                $slotsList[] = [
                    'slot_id' => $slot->id,
                    'date' => $slot->slot_date->format('Y-m-d'),
                    'free_capacity' => $slot->capacity - $booked,
                    'filled_capacity' => $booked,
                ];
            }
        }
        if (!empty($slotsList)) {
            return successResponse(trans('api.classes.yes_slot'), $slotsList);
        } else {
            return errorResponse(trans('api.classes.no_slot'));
        }
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:App\GeneralClass,id',
            'package_id' => 'required|exists:App\Package,id',
            'payment_method' => 'required', //alias payment_type
            'card_id' => 'required_if:payment_method,==,credit_card', //alias payment_type
            'slot_id.*' => 'required|exists:App\GeneralClassSlots,id',
            // 'order_type' => 'required',
        ]);

        $order_id = generateOrderNumber();
        $slotIds = $request->slot_id;

        $generalClass = GeneralClass::where('id', $request->class_id)->first();
        $package = Package::where('id', $request->package_id)->first();

        $vatAmount = ($package->price + $package->service_charge) * ((float) config('settings.vat') / 100);

        $order = new ClassOrder();
        $order->order_id = $order_id;
        $order->user_id = $request->user()->id;
        $order->vat_percentage = config('settings.vat');
        $order->amount_exclusive_vat = $package->price;
        $order->service_charge = $package->service_charge ?? 0;
        $order->vat_amount = $vatAmount;
        $order->total_amount = $package->price + $vatAmount + $package->service_charge;
        $order->payment_type = $request->payment_method;
        $order->card_id = $request->card_id ?? "";
        $order->class_type = $generalClass->type;
        $order->class_id = $request->class_id;
        $order->class_name = $generalClass->title;
        $order->package_id = $request->package_id;
        $order->package_name = $package->name;
        $order->package_price = $package->price;
        $order->package_price = $package->price;
        $order->order_type = "Normal";
        $order->notes = $request->notes ?? "";
        $order->save();
        $order->classOrderStatusHistory()->save(new ClassOrderStatus(['status' => 'submitted']));
        $slots = GeneralClassSlots::whereIn('id', $slotIds)->get();
        foreach ($slots as $key => $slot) {
            $data[] = new ClassOrderSlot([
                'slot_id' => $slot->id,
                'slot_date' => $slot->slot_date,
                'start_at' => $slot->start_at,
                'end_at' => $slot->end_at,
            ]);

            GeneralClassAttendees::create([
                'class_id' => $request->class_id,
                'slot_id' => $slot->id,
                'user_id' => $request->user()->id,
                'enrolled_at' => $order->created_at,
            ]);
        }
        $order->orderSlots()->saveMany($data);
        if ($request->payment_method == "credit_card") {
            //Payment Process
            $paymentSuccess = $this->processPayment($order);
            if (isset($paymentSuccess['error'])) {
                $order->payment_status = 3;
                $order->save();
                return errorResponse($paymentSuccess['message'], $paymentSuccess['title']);
            }
            if ($paymentSuccess) {
                $order->payment_status = 1;
                $order->save();
            }
        }
        // return new ClassOrderCollection($order);
        return successResponse(trans('api.classes.created'), new ClassOrderResource($order));
    }

    public function orderDetails(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        $order = ClassOrder::with('orderSlots')->where('id', $request->order_id)->get();
        return new ClassOrderCollection($order);
    }

    public function myorderHistory(Request $request)
    {
        $myorder = ClassOrder::with('orderSlots')->where('user_id', $request->user()->id)->orderBy('created_at', 'desc');
        if ($request->order_type) {
            $myorder->where('order_type', $request->order_type);
        }
        if ($request->class_type) {
            $myorder->where('class_type', $request->class_type);
        }
        if (!empty($myorder)) {
            return new ClassOrderCollection($myorder->paginate($request->limit ?? 20));
        } else {
            return errorResponse(trans('api.classes.no_data'));
        }
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
            $amount = (int) floor($order->total_amount);
        }
        $payment = $this->chargeCustomer($cardDetails->si_reference, "CLASS" . $order->order_id, $amount);
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
            ClassOrderPayment::create($data);
            //transaction success
            if ($payment['si_charge_status'] == 0 && $payment['si_charge_txn_status'] == 0) {
                return true;
            } else {
                return ['error' => true, 'message' => 'Payment could not be processed', 'title' => 'Payment Failed'];
            }
        }
        return ['error' => true, 'message' => 'Unknown Error in Payment', 'title' => 'Payment Failed'];
    }

    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:App\ClassOrder,id',
            'slot_id' => 'nullable|exists:App\ClassOrderSlot,id',
        ]);
        $order = ClassOrder::with('orderSlots')->where('id', $request->order_id)->first();
        if ($request->slot_id) {
            $firstSlot = $order->orderSlots()->withTrashed()->where('id', $request->slot_id)->orderBy('slot_date', 'ASC')->orderBy('start_at', 'ASC')->first();
        } else {
            $firstSlot = $order->orderSlots()->withTrashed()->orderBy('slot_date', 'ASC')->orderBy('start_at', 'ASC')->first();
        }
        $cancelDuration = config('settings.class_order_cancel_duration');
        $slot_date = Carbon::parse($firstSlot->slot_date)->format('Y-m-d');
        $start_at = Carbon::createFromFormat("h:i A", $firstSlot->start_at)->format('H:i:s');
        $slotTime = Carbon::parse($slot_date . " " . $start_at);
        $currentTime = Carbon::now();
        $totalDuration = $currentTime->diffInMinutes($slotTime);
        if ($totalDuration < $cancelDuration) {
            return errorResponse(trans('api.classes.cancel_timeout'));
        }
        if ($request->slot_id) {
            $selectedSlot = $order->orderSlots()->where('id', $request->slot_id)->delete();
            return successResponse(trans('api.classes.order_slot_cancelled'));
        } else {
            $order->order_status = "cancelled";
            $order->save();
            return successResponse(trans('api.classes.order_cancelled'));
        }
    }

    public function reportProblem(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'subject' => 'required',
            'details' => 'required',
        ]);
        $reportProblem = ClassReportProblem::create([
            'name' => $request->subject,
            'description' => $request->details,
            'order_id' => $request->order_id,
            'user_id' => $request->user()->id,
        ]);
        if ($reportProblem) {
            return successResponse(trans('api.classes.issue_added'), null);
        } else {
            return errorResponse(trans('api.classes.issue_error'));
        }
    }
}
