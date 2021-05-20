<?php

namespace App\Http\Controllers\Services;

use App\ServiceCart;
use App\Store;
use App\ServiceProducts;
use App\StoreServiceProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceStoreProductPlainCollection;
use App\Order;
use App\OrderItem;
use App\ServiceOrder;
use App\StoreDaysSlot;
use Carbon\Carbon;
use Helper;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'store_id' => 'required'
        ]);
        if (!empty($this->cartContents($request))) {
            return successResponse(trans('api.cart.list'), $this->cartContents($request));
        } else {
            return errorResponse(trans('api.cart.list_empty'), null);
        }
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
            'store_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        $store_product = StoreServiceProducts::with('product:id')->where('store_id', $request->store_id)
            ->where('id', $request->product_id)
            ->first();
        if (!$store_product) {
            return errorResponse(trans('api.cart.store_product_not_exists'), null);
        }

        $items = $this->addToCart($store_product, $request->quantity, $request->user()->id);
        if (isset($items['error'])) {
            return errorResponse(trans('api.cart.' . $items['data'][0]), null);
        }
        $contents = $this->cartContents($request);
        if (!empty($contents)) {
            return successResponse(trans('api.cart.added'), $contents);
        }
        return successResponse(trans('api.cart.list_empty'));
    }

    public function cartContents(Request $request)
    {
        $cart = null;
        $total = 0;
        $totalQty = 0;
        $contents = \DB::select('select `service_carts`.`id`, `service_carts`.`quantity`, `service_store_products`.`unit_price`, `service_products`.`name`, `service_products`.`image`, `service_store_products`.`store_id`, `service_store_products`.`id` as `product_id`, `stores`.`time_to_deliver` from `service_carts` inner join `service_store_products` on `service_carts`.`store_id` = `service_store_products`.`store_id` and `service_carts`.`product_id` = `service_store_products`.`id` inner join `service_products` on `service_products`.`id` = `service_store_products`.`product_id`  inner join `stores` on `stores`.`id` = `service_carts`.`store_id` where `service_carts`.`store_id` = "' . $request->store_id . '" and `service_carts`.`user_id` = "' . $request->user()->id . '" and `service_store_products`.`deleted_at` is null and `service_carts`.`deleted_at` is null and `service_carts`.`deleted_at` is null');
        $time_to_deliver = "";
        if (!empty($contents)) {
            foreach ($contents as $content) {
                $time_to_deliver = $content->time_to_deliver;
                $content->unit_price = round_my_number($content->unit_price);
                $total += $content->quantity * $content->unit_price;
                $totalQty += $content->quantity;
                unset($content->time_to_deliver);
                $content->image = ($content->image != NULL) ? Helper::imageUrl('serviceProduct', $content->image) : NULL;
            }
            $cart['items'] = $contents;
            $store = getStore($request->store_id);
            $serviceCharge = $store->service_charge ?? 0;
            $vat = round_my_number(((float) config('settings.vat') / 100) * ($total + $serviceCharge));
            $cart['total_items'] = count($contents);
            $cart['total_no_cart_items'] = $totalQty;
            $cart['vat_amount'] = $vat;
            $cart['exclude_tax'] = round_my_number($total);
            $cart['service_charge'] = round_my_number($serviceCharge);
            $cart['cart_total'] = round_my_number($total + $vat + $serviceCharge);
            $cart['time_to_deliver'] = $time_to_deliver;
        }
        return $cart;
    }

    public function addToCart($store_product, $qty, $user_id)
    {
        $cart = ServiceCart::where('store_id', $store_product->store_id)
            ->where('product_id', $store_product->id)
            ->where('user_id', $user_id)
            ->first();
        if (!$cart) {
            $cart = new ServiceCart();
            $cart->store_id = $store_product->store_id;
            $cart->product_id = $store_product->id;
            $cart->user_id = $user_id;
        }
        $total_qty = ($cart->quantity + $qty);
        if ($total_qty < 1) {
            $cart->delete();
            return $cart;
        }
        $cart->quantity = $total_qty;
        $cart->save();
        return $cart;
    }

    public function removeCart(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        ServiceCart::where('store_id', $request->store_id)->where('user_id', $request->user()->id)->delete();
        return successResponse(trans('api.cart.clear_all'), $this->cartContents($request));
    }


    public function orderAgain(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        $order = ServiceOrder::find($request->order_id);
        foreach ($order->orderItem as $product) {
            $items = $this->addToCart($product->serviceStoreProduct, $product->quantity, $request->user()->id);
        }
        $request->store_id = $order->store_id;
        $contents = $this->cartContents($request);
        if (!empty($contents)) {
            return successResponse(trans('api.cart.added'), $contents);
        }
        return successResponse(trans('api.cart.list_empty'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceCart $cart)
    {
        $cart->delete();
        return successResponse(trans('api.cart.deleted'));
    }
}
