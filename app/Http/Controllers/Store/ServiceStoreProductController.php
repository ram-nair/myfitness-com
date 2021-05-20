<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseController;
use App\Imports\ServiceStoreProductsImport;
use App\ServiceCart;
use App\Store;
use App\StoreServiceProducts;
use Auth;
use Helper;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class ServiceStoreProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $guard_name = Helper::get_guard();

        $req_id = 1;
        if ($guard_name == "admin") {
            $req_id = isset($request->id) ? $request->id : 1;
        } elseif ($request->user()->service_type == "service_type_2") {
            $req_id = 2;
        } elseif ($request->user()->service_type == "service_type_3") {
            $req_id = 3;
        }
        // $req_id = (($guard_name == "admin") ? (isset($request->id) ? $request->id : 1) : (($request->user()->service_type == "service_type_2") ? 2 : 3));
        // dd($req_id);
        //return $req_id;
        //$req_id = ($guard_name == "admin") ? $request->id : ($request->user()->service_type == "service_type_2") ? 2 : ($request->user()->service_type == "service_type_3")?3:1;

        // $req_id = $guard_name == "admin" ? $request->id : $request->user()->service_type == "service_type_2" ? 2 : 1;
        $this->authorize('viewAny', [StoreServiceProducts::class, $req_id]);
        $store_type = 'service_type_1';
        if ($req_id == 2) {
            $store_type = 'service_type_2';
        } elseif ($req_id == 3) {
            $store_type = 'service_type_3';
        }
        $this->setPageTitle("Service {$request->id} Store Products", 'Products List');
        $stores = Store::where('business_type_id', 3)->where('active', 1)->where('service_type', $store_type)->get()->sortBy('name');
        $guard_name = Helper::get_guard();
        return view('services.store-products.index', compact('stores', 'guard_name', 'store_type', 'req_id'));
    }

    public function datatable(Request $request)
    {
        $guard_name = Helper::get_guard();
        $currentUser = Auth::user();
        $isStoreUser = $currentUser->hasRole('store');
        if ($isStoreUser) {
            $products = StoreServiceProducts::with(['product', 'store'])->where('store_id', $currentUser->id)->select('*');
        } else {
            $products = StoreServiceProducts::with(['product', 'store'])->select('*');
        }
        if ($request->vendor_id) {
            $products = $products->where('vendor_id', $request->vendor_id);
        }
        if ($request->store_type == 'service_type_3') {
            $service_type = 3;
        } elseif ($request->store_type == "service_type_2") {
            $service_type = 2;
        } else {
            $service_type = 1;
        }

        if ($request->store_type && !$currentUser->service_type) {
            //  $service_type = ($request->store_type == 'service_type_1') ? 1 : ($request->store_type == 'service_type_2') ? 2 : 3;
            $products = $products->whereHas('product', function ($q) use ($service_type) {
                $q->where('service_type', $service_type);
            });
        }
        if ($currentUser->service_type) {
            //  $service_type = ($request->store_type == 'service_type_1') ? 1 : ($request->store_type == 'service_type_2') ? 2 : 3;
            $products = $products->whereHas('product', function ($q) use ($service_type) {
                $q->where('service_type', $service_type);
            });
        }
        if ($request->store_id) {
            $products = $products->where('store_id', $request->store_id);
        }
        return Datatables::of($products)
            ->rawColumns(['actions'])
            ->editColumn('product_id', function ($storeproduct) {
                return $storeproduct->product->name ? $storeproduct->product->name : "-";
            })
            ->editColumn('store.store_fullname', function ($storeproduct) use ($isStoreUser) {
                if (!$isStoreUser) {
                    return $storeproduct->store->store_name ?? " ";
                }
                return "";
            })
            ->editColumn('categories', function ($storeproduct) use ($service_type) {
                if ($service_type != "3") {
                    return $storeproduct->product->mainCategory->name ?? "";
                } else {
                    return "";
                }
            })
            ->editColumn('price_approved', function ($storeproduct) {
                return $storeproduct->price_approved;
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($products) use ($currentUser, $isStoreUser, $guard_name, $service_type) {
                $b = $extraclass = '';
                $editUrl = URL::route('store.service-store-products.edit', ['service_store_product' => $products->id, 'sid' => $service_type]);
                $deleteUrl = URL::route('store.service-store-products.destroy', $products->id);
                if (!$isStoreUser) {
                    $editUrl = URL::route('admin.service-store-products.edit', ['service_store_product' => $products->id, 'sid' => $service_type]);
                    $deleteUrl = URL::route('admin.service-store-products.destroy', $products->id);
                    $extraclass = 'ajax-modal';
                }
                $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs ' . $extraclass . '" data-target="#ajaxModal"><i class="fa fa-edit"></i></a>';
                if ($products->store) {
                    $count = ServiceCart::where('product_id', $products->id)->where('store_id', $products->store->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    }
                    return $b;
                }
                $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $product = StoreServiceProducts::find($id);
        $guard_name = Helper::get_guard();
        // $req_id = $guard_name == "admin" ? $request->id : $request->user()->service_type == "service_type_2" ? 2 : 1;

        // $req_id = (($guard_name == "admin") ? (isset($request->sid) ? $request->sid : 1) : (($request->user()->service_type == "service_type_2") ? 2 : 3));
        $req_id = 1;
        if ($guard_name == "admin") {
            $req_id = isset($request->sid) ? $request->sid : 1;
        } elseif ($request->user()->service_type == "service_type_2") {
            $req_id = 2;
        } elseif ($request->user()->service_type == "service_type_3") {
            $req_id = 3;
        }
        $this->authorize('update', [$product, $req_id]);
        $this->setPageTitle('Store Products', 'Products Details Edit');
        if ($request->ajax()) {
            return view('services.store-products.ajax', compact('product', 'guard_name'));
        }
        return view('services.store-products.edit', compact('product', 'guard_name', 'req_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $storeProduct = StoreServiceProducts::find($id);
        $guard_name = Helper::get_guard();

        // $req_id = (($guard_name == "admin") ? (isset($request->sid) ? $request->sid : 1) : (($request->user()->service_type == "service_type_2") ? 2 : 3));
        // $req_id = $guard_name == "admin" ? $request->id : $request->user()->service_type == "service_type_2" ? 2 : $request->user()->service_type == "service_type_3" ? 3 : 1;
        //  $req_id = $guard_name == "admin" ? $request->sid : $request->user()->service_type == "service_type_2" ? 2 : 1;
        $req_id = 1;
        if ($guard_name == "admin") {
            $req_id = isset($request->sid) ? $request->sid : 1;
        } elseif ($request->user()->service_type == "service_type_2") {
            $req_id = 2;
        } elseif ($request->user()->service_type == "service_type_3") {
            $req_id = 3;
        }
        $this->authorize('update', [$storeProduct, $req_id]);
        if ($guard_name == "admin" && $request->approve == 1) {
            $storeProduct->price_approved = 0;
            $storeProduct->unit_price = $storeProduct->ask_price;
        } elseif ($guard_name == "admin" && $request->reject == 1) {
            $storeProduct->price_approved = 2;
        }
        if ($guard_name == "store") {
            $storeProduct->ask_price = $request->ask_price;
            if ($storeProduct->unit_price != $request->ask_price) {
                $storeProduct->price_approved = 1;
            }
        }

        $storeProduct->update();
        alert()->success('Product details updated successfully.', 'Updated');
        return redirect()->route($guard_name . '.service-store-products.index', ['id' => $req_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $storeServiceProducts = StoreServiceProducts::find($id);
        $guard_name = Helper::get_guard();
        $req_id = 1;
        if ($guard_name == "admin") {
            $req_id = isset($request->sid) ? $request->sid : 1;
        } elseif ($request->user()->service_type == "service_type_2") {
            $req_id = 2;
        } elseif ($request->user()->service_type == "service_type_3") {
            $req_id = 3;
        }
        $this->authorize('update', [$storeServiceProducts, $req_id]);
        $storeServiceProducts->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function reject($id)
    {
        $guard_name = Helper::get_guard();
        $storeProduct = StoreServiceProducts::find($id);
        $storeProduct->price_approved = 2;
        $storeProduct->save();
        alert()->success('Product details updated successfully.', 'Updated');
        return redirect()->route($guard_name . '.service-store-products.index', ['id' => $req_id]);
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'store-product_csv ' => 'required|mimes:csv',
        // ]);

        $storeIds = $request->store_id;
        if ($storeIds[0] === "all") {
            $stores = Store::where('business_type_id', 3)->where('service_type', $request->store_type)->get()->pluck('id');
        } else {
            $stores = $storeIds;
        }
        $file = $request->file('products_csv');
        $import = new ServiceStoreProductsImport($stores);
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.service-store-products.index')->withSuccess($import->getTotalCount());
    }
}
