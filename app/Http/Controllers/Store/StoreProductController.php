<?php

namespace App\Http\Controllers\Store;

use App\Cart;
use App\Http\Controllers\BaseController;
use App\Imports\StoreProductsImport;
use App\Store;
use App\StoreProduct;
use Helper;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class StoreProductController extends BaseController
{
    public function __construct()
    {
        // $this->authorizeResource(StoreProduct::class, 'storeproducts');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StoreProduct::class);
        $this->setPageTitle('Store Products', 'Products List');
        $stores = Store::where('business_type_id', 1)->where('active', 1)->get()->sortBy('name');
        $guard_name = Helper::get_guard();
        return view('admin.store-products.index', compact('stores', 'guard_name'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $isStoreUser = $currentUser->hasRole('store');
        if ($isStoreUser) {
            $products = StoreProduct::with(['product', 'store'])->where('store_id', $currentUser->id)->select('*');
        } else {
            $products = StoreProduct::with(['product', 'store'])->select('*');
        }
        if ($request->vendor_id) {
            $products = $products->where('vendor_id', $request->vendor_id);
        }
        if ($request->store_id) {
            $products = $products->where('store_id', $request->store_id);
        }
        if ($request->price_approval) {
            $products = $products->where('price_approved', $request->price_approval);
        }
        return Datatables::of($products)
            ->rawColumns(['actions'])
            ->editColumn('stock', function ($storeproduct) {
                return $storeproduct->stock;
            })
            ->editColumn('store.store_fullname', function ($storeproduct) use ($isStoreUser) {
                if (!$isStoreUser) {
                    return $storeproduct->store->store_name;
                }
                return "";
            })
            ->editColumn('categories', function ($storeproduct) {
                return $storeproduct->product->mainCategory->name;
            })
            ->editColumn('price_approved', function ($storeproduct) {
                return $storeproduct->price_approved;
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($products) use ($currentUser, $isStoreUser) {
                $b = $extraclass = '';
                $editUrl = URL::route('store.store-products.edit', $products->id);
                $deleteUrl = URL::route('store.store-products.destroy', $products->id);
                if (!$isStoreUser) {
                    $editUrl = URL::route('admin.store-products.edit', $products->id);
                    $deleteUrl = URL::route('admin.store-products.destroy', $products->id);
                    $extraclass = 'ajax-modal';
                }
                if ($currentUser->hasPermissionTo('storeproduct_update')) {
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs ' . $extraclass . '" data-target="#ajaxModal"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('storeproduct_delete')) {
                    if ($products->store) {
                        $count = Cart::where('product_id', $products->id)->where('store_id', $products->store->id)->count();
                        if ($count >= 1) {
                            $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy no-delete"><i class="fa fa-trash"></i></a>';
                        } else {
                            $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                        }
                    }
                }
                return $b;
            })->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $guard_name = Helper::get_guard();
        $product = StoreProduct::find($id);
        $this->authorize('update', $product);
        $this->setPageTitle('Store Products', 'Products Details Edit');
        if ($request->ajax()) {
            return view('admin.store-products.ajax', compact('product', 'guard_name'));
        }
        return view('admin.store-products.edit', compact('product', 'guard_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreProduct $storeProduct)
    {
        $guard_name = Helper::get_guard();
        $this->authorize('update', $storeProduct);
        if ($guard_name == "admin" && $request->approve == 1) {
            $storeProduct->price_approved = 0;
            $storeProduct->unit_price = $storeProduct->ask_price;
        } elseif ($guard_name == "admin" && $request->reject == 1) {
            $storeProduct->price_approved = 2;
        }
        if ($guard_name == "store") {
            $storeProduct->stock = $request->stock;
            $storeProduct->out_of_stock = $request->has('out_of_stock') ? 1 : 0;
            $storeProduct->quantity_per_person = $request->quantity_per_person;
            $storeProduct->ask_price = $request->ask_price;
            if ($storeProduct->unit_price != $request->ask_price) {
                $storeProduct->price_approved = 1;
            }
        }

        $storeProduct->update();
        alert()->success('Product details updated successfully.', 'Updated');
        return redirect()->route($guard_name . '.store-products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreProduct $storeProduct)
    {
        $this->authorize('delete', $storeProduct);
        $storeProduct->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function reject($id)
    {
        $guard_name = Helper::get_guard();
        $storeProduct = StoreServiceProducts::find($id);
        $storeProduct->price_approved = 2;
        $storeProduct->save();
        alert()->success('Product details updated successfully.', 'Updated');
        return redirect()->route($guard_name . '.store-products.index');
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'products_csv ' => 'required|mimes:xls,xlsx',
        // ]);
        $file = $request->file('products_csv');
        $storeIds = $request->store_id;
        if ($storeIds[0] === "all") {
            $stores = Store::where('business_type_id', 1)->get()->pluck('id');
        } else {
            $stores = $storeIds;
        }
        $import = new StoreProductsImport($stores);
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.store-products.index')->withSuccess($import->getTotalCount());
    }
}
