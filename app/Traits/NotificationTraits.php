<?php

namespace App\Traits;

use App\Events\TwilioPush;
use App\Http\Resources\OrderItemCollection;
use App\Http\Resources\ServiceOrderResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\StoreProductPlainCollection;
use App\Http\Resources\StoreResource;
use App\Notification;
use Config;
use Lang;

trait NotificationTraits
{

    public function createOrderStatusPush($user, $type, $data, $order_type = "ECOM")
    {
        $messageTypeId = config("globalconstants.NOTIFICATION_MESSAGE_TYPE.{$order_type}_ORDER_STATUS_CHANGED");
        $groupType = config("globalconstants.NOTIFICATION_CATEGORY.{$order_type}_ORDER");
        $status = trans("api.order_status.{$order_type}.{$data->order_status}");
        $pushTitle = trans('api.order.status_change.title', ['status' => $status]);
        $pushBody = trans('api.order.status_change.body', ['order' => $data->order_id, 'status' => $status]);

        $userImage = null;
        $userName = $user->phone;

        $notification = new Notification();
        $notification->categoryId = $groupType;
        $notification->status = 0;
        $notification->userId = $user->id;
        $notification->title = $pushTitle;
        $notification->message = $pushBody;
        $notification->messageType = $messageTypeId;
        $notification->save();

        $order_from = $ecommerce_order = $service_order = null;
        if ($order_type == "ECOM") {
            $ecommerce_order = new OrderResource($data);
            $order_from = "ecommerce";
        } else {
            $service_order = new ServiceOrderResource($data);
            $order_from = $data->service_type;
        }

        $pushData = [
            "id" => $notification->id,
            "groupType" => $notification->categoryId,
            "notificationType" => $notification->messageType,
            "dataId" => $notification->messageTypeId,
            "store" => new StoreResource($data->store),
            "ecommerce_order" => $ecommerce_order,
            "service_order" => $service_order,
            "order_from" => $order_from,
        ];
        $notification->data = $pushData;
        $notification->save();

        unset($pushData['store']);
        unset($pushData['ecommerce_order']);
        unset($pushData['service_order']);

        event(new TwilioPush($user, $pushTitle, $pushBody, $pushData, 'customer'));
        return true;
    }

    public function createOrderPush($user, $type, $data)
    {
        $messageType = config('globalconstants.NOTIFICATION_MESSAGE_TYPE');
        $groupType = config('globalconstants.NOTIFICATION_CATEGORY.ORDER');

        $testDataTitle = 'Test';
        switch ($type) {
            case $messageType['ORDER_PLACED']:
                $messageTypeId = config('globalconstants.NOTIFICATION_MESSAGE_TYPE.ORDER_PLACED');
                $title = ['api.order.placed.title', []];
                $body = ['api.order.placed.body', ['amount' => $data['amount'], 'currency' => env('SITE_CURRENCY')]];
                break;

            case $messageType['S1_ORDER_STATUS_CHANGED']:
                $messageTypeId = config('globalconstants.NOTIFICATION_MESSAGE_TYPE.S1_ORDER_STATUS_CHANGED');
                $title = ['api.order.status_change.title', ['status' => $data['status']]];
                $body = ['api.order.status_change.body', ['order' => $data['amount'], 'from' => $data['from'], 'to' => $data['to']]];
                break;

            default:
                return true;
                break;
        }

        $pushTitle = Lang::get($title[0], $title[1], 'en');
        $pushBody = Lang::get($body[0], $body[1], 'en');

        $userImage = null;
        $userName = $user->mobile;

        $notification = new Notification();
        $notification->categoryId = $groupType;
        $notification->status = 0;
        $notification->title = $pushTitle;
        $notification->message = $pushBody;
        $notification->messageType = $messageTypeId;
        $notification->save();

        $pushData = '{'
            . '"id":"' . $notification->id . '",' .
            '"groupType":' . $notification->categoryId . ',' .
            '"notificationType":' . $notification->messageType . ',' .
            '"dataId":"' . $messageTypeId . '",' .
            '"dataTitle":"' . $testDataTitle . '",' .
            '"image":"' . $userImage . '",' .
            '"name":"' . $userName . '"' .
            '}';

        $notification->data = $pushData;
        $notification->save();

        event(new TwilioPush($user, $pushTitle, $pushBody, $pushData, 'customer'));
        return true;
    }

    public function orderItemOutOfStockPush($user, $type, $data, $outOfStockItems, $order_type = "ECOM")
    {
        $messageTypeId = config("globalconstants.NOTIFICATION_MESSAGE_TYPE.{$order_type}_ORDER_ITEM_OUTOFSTOCK");
        $groupType = config("globalconstants.NOTIFICATION_CATEGORY.{$order_type}_ORDER_ITEM_OUTOFSTOCK");
        $pushTitle = trans('api.order.item_outofstock.title', ['order' => $data->order_id]);
        $pushBody = trans('api.order.item_outofstock.body');

        $notification = new Notification();
        $notification->categoryId = $groupType;
        $notification->status = 0;
        $notification->userId = $user->id;
        $notification->title = $pushTitle;
        $notification->message = $pushBody;
        $notification->messageType = $messageTypeId;
        $notification->save();

        $ecommerce_order = new OrderResource($data);
        $order_items = new StoreProductPlainCollection($outOfStockItems);

        $pushData = [
            "id" => $notification->id,
            "groupType" => $notification->categoryId,
            "notificationType" => $notification->messageType,
            "dataId" => $notification->messageTypeId,
            "store" => new StoreResource($data->store),
            "ecommerce_order" => $ecommerce_order,
            'out_of_stock_items' => $order_items,
            "order_from" => "ecommerce",
        ];

        $notification->data = $pushData;
        $notification->save();

        unset($pushData['store']);
        unset($pushData['ecommerce_order']);
        unset($pushData['out_of_stock_items']);

        event(new TwilioPush($user, $pushTitle, $pushBody, $pushData, 'customer'));
        return true;
    }
}
