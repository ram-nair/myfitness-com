<?php

use App\OrderItem;
use App\ServiceOrderItem;
use App\Store;
use Carbon\Carbon;

function cdn($asset)
{
    //Check if we added cdn's to the config file
    if (!env('CDN_ENABLED', false)) {
        return Storage::disk('public')->url($asset);
    } else {
        return Storage::disk('s3')->url(env('CDN_FILE_DIR', 'dev/test/') . $asset);
    }
}

function round_my_number($number)
{
    return number_format((float) $number, 2, '.', '');
}

function successResponse($status, $data = null)
{
    if (!empty($data)) {
        return response()->json([
            "statusCode" => 200,
            "message" => $status,
            "data" => $data,
        ]);
    } else {
        return response()->json([
            "statusCode" => 200,
            "message" => $status,
        ]);
    }
}

function errorResponse($status, $errorMessage = null)
{
    return response()->json([
        "statusCode" => 401,
        "message" => $status,
        "errorMessage" => $errorMessage,
    ]);
}

function systemResponse($status)
{
    return response()->json([
        "statusCode" => 401,
        "message" => $status,
    ]);
    //return response()->json(['error' => $status, 401]);
}

function getAllDates($start, $end)
{
    $startDate = Carbon::createFromFormat('d/m/Y', $start);
    $endDate = Carbon::createFromFormat('d/m/Y', $end);
    $all_dates = array();
    while ($startDate->lte($endDate)) {
        $all_dates[] = $startDate->toDateString();
        $startDate->addDay();
    }
    return $all_dates;
}

function generateOrderNumber()
{
    $number = Hashids::encode(mt_rand()); // better than rand()
    return $number;
}

function getStore($id)
{
    $store = Store::find($id);
    return $store;
}

function calculateECommerceOrderSum($order)
{
    $orderItems = OrderItem::where('order_id', $order->id)->get();
    $vat = config('settings.vat');
    $serviceCharge = $order->store->service_charge ?? 0;
    $totalAmount = 0;
    foreach ($orderItems as $item) {
        $totalAmount += $item->total_amount;
    }
    $itemsTotal = $totalAmount + $serviceCharge;
    $vatAmount = $itemsTotal * ((float) $vat / 100);
    $orderTotal = $itemsTotal + $vatAmount;
    $order->amount_exclusive_vat = $totalAmount;
    $order->vat_amount = $vatAmount;
    $order->total_amount = $orderTotal;
    $order->save();
    return $order;
}

function calculateServiceOrderSum($order)
{
    $orderItems = ServiceOrderItem::where('order_id', $order->id)->get();
    $vat = config('settings.vat');
    $serviceCharge = $order->store->service_charge ?? 0;
    $onMyLocationCharge = $order->store->on_my_location_charge ?? 0;
    $totalAmount = 0;
    foreach ($orderItems as $item) {
        $totalAmount += $item->total_amount;
    }
    $itemsTotal = $totalAmount + $serviceCharge;
    $vatAmount = $itemsTotal * ((float) $vat / 100);
    $orderTotal = $itemsTotal + $vatAmount + $onMyLocationCharge;
    $order->amount_exclusive_vat = $totalAmount;
    $order->vat_amount = $vatAmount;
    $order->total_amount = $orderTotal;
    $order->save();
    return $order;
}

function getPaymentStatus($status)
{
    switch ($status) {
        case "1":
            $r = "Paid";
            break;
        case "2":
            $r = "Cancelled";
            break;
        case "3":
            $r = "Failed";
            break;

        default:
            $r = "Pending";
            break;
    }
    return $r;
}

//polygon to cordinates
function sql_to_coordinates($blob)
{
    $blob = str_replace("))", "", str_replace("POLYGON((", "", $blob));
    $coords = explode(",", $blob);
    $coordinates = array();
    foreach ($coords as $coord) {
        $coord_split = explode(" ", $coord);
        $coordinates[] = array("lat" => (float) $coord_split[0], "lng" => (float) $coord_split[1]);
    }
    return $coordinates;
}
