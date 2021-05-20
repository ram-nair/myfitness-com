<?php

namespace App\Http\Controllers\Customer;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderPlainCollection;
use App\Http\Resources\StoreProductPlainCollection;
use App\Order;
use App\OrderItem;
use App\ProductImage;
use App\Store;
use App\StoreProduct;
use Illuminate\Http\Request;

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
            'store_id' => 'required',
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
        $store_product = StoreProduct::with('product:id')->where('store_id', $request->store_id)->where('id', $request->product_id)->first();
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
        $contents = \DB::select('select `carts`.`id`, `carts`.`quantity`, `product_stores`.`unit_price`, `product_stores`.`id` as `product_id`, `products`.`name`, `products`.`id` as `product_og_id`, `products`.`quantity` as quantity_label, `products`.`unit` as unit_label,`products`.`sub_category_id`, `product_stores`.`store_id`, `product_stores`.`quantity_per_person`, `stores`.`time_to_deliver` from `carts` inner join `product_stores` on `carts`.`store_id` = `product_stores`.`store_id` and `carts`.`product_id` = `product_stores`.`id` inner join `products` on `products`.`id` = `product_stores`.product_id  inner join `stores` on `stores`.`id` = `carts`.`store_id` where `carts`.`store_id` = "' . $request->store_id . '" and `carts`.`user_id` = "' . $request->user()->id . '" and `product_stores`.`deleted_at` is null and `carts`.`deleted_at` is null and `carts`.`deleted_at` is null');
        $time_to_deliver = "";
        if (!empty($contents)) {
            foreach ($contents as $content) {
                $image = ProductImage::where('product_id', $content->product_og_id)->first();
                $time_to_deliver = $content->time_to_deliver;
                unset($content->time_to_deliver);
                $content->unit_price = round_my_number($content->unit_price);
                $total += $content->quantity * $content->unit_price;
                $totalQty += $content->quantity;
                $content->image = $image->full ?? null;
            }
            $cart['items'] = $contents;
            $store = getStore($request->store_id);
            $serviceCharge = $store->service_charge ?? 0;
            // $total = $total + $serviceCharge;
            $vat = round_my_number(((float) config('settings.vat') / 100) * ($total + $serviceCharge));
            $cart['time_to_deliver'] = $time_to_deliver;
            $cart['total_items'] = count($contents);
            $cart['total_no_cart_items'] = $totalQty;
            $cart['vat_amount'] = $vat;
            $cart['service_charge'] = round_my_number($serviceCharge);
            $cart['exclude_tax'] = round_my_number($total);
            $cart['cart_total'] = round_my_number($total + $vat + $serviceCharge);
        }
        return $cart;
    }

    public function addToCart($store_product, $qty, $user_id)
    {
        $cart = Cart::where('store_id', $store_product->store_id)->where('user_id', $user_id)->where('product_id', $store_product->id)->first();

        $errors = [];
        if ($store_product->out_of_stock || $store_product->stock < 1) {
            $errors['data'][] = "OUTOFSTOCK";
        }
        if ($store_product->quantity_per_person < $qty) {
            $errors['data'][] = "QUANTITYEXCEED";
        }
        if ($cart && $store_product->stock < $cart->quantity) {
            $errors['data'][] = "OUTOFSTOCK";
        }
        if ($cart && $store_product->quantity_per_person < $cart->quantity + $qty) {
            $errors['data'][] = "QUANTITYEXCEED";
        }

        if (isset($errors) && !empty($errors['data'])) {
            return ['error' => true] + $errors;
        }
        if (!$cart) {
            $cart = new Cart();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart, Request $request)
    {
        $request->store_id = $cart->store_id;
        $request->product_id = $cart->product_id;
        $cart->delete();
        return successResponse(trans('api.cart.deleted'), $this->cartContents($request));
    }

    public function removeCart(Request $request)
    {
        $contents = Cart::where('store_id', $request->store_id)
            ->where('user_id', $request->user()->id)->delete();
        return successResponse(trans('api.cart.clear_all'), $this->cartContents($request));
    }

    public function orderAgain(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);
        $outOfStock = [];
        $order = Order::find($request->order_id);
        foreach ($order->orderItem as $product) {
            $items = $this->addToCart($product->storeProduct, $product->quantity, $request->user()->id);
            if (isset($items['error']) && !empty($items['error'])) {
                $outOfStock[] = $product->storeProduct;
            }
        }
        $request->store_id = $order->store_id;
        $contents = $this->cartContents($request);
        $contents['out_of_stock']['items'] = new StoreProductPlainCollection($outOfStock);
        if (!empty($contents)) {
            return successResponse(trans('api.cart.added'), $contents);
        }
        return successResponse(trans('api.cart.list_empty'));
        return response()->json([
            'statusCode' => 200,
            'message' => trans('api.order.list'),
            'data' => [
                "order_data" => new OrderPlainCollection($order),
                "out_of_stock" => new StoreProductPlainCollection($outOfStock),
            ],
        ]);
    }
}
