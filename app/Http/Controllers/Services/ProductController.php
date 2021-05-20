<?php

namespace App\Http\Controllers\Services;

use App\Category;
use App\ClearingMaterials;
use App\Contracts\ServiceProductContract;
use App\Http\Controllers\BaseController;
use App\Http\Resources\EcommerceBannerCollection;
use App\Http\Resources\ServiceCategoryResource;
use App\Http\Resources\ServiceStoreProductCollection;
use App\Http\Resources\ServiceStoreProductResource;
use App\Http\Resources\StoreProductCollection;
use App\Imports\ServiceProductsImport;
use App\Imports\ServiceTypeThreeProductsImport;
use App\Imports\ServiceTypeTwoProductsImport;
use App\ServiceBanner;
use App\ServiceProducts;
use App\Store;
use App\StoreServiceProducts;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class ProductController extends BaseController
{
    use ImageTraits;

    protected $productRepository;

    public function __construct(ServiceProductContract $productRepository, Request $request)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {

        $this->authorize('viewAny', [ServiceProducts::class, $request->id]);

        $service_type = $request->id ?? 1;
        $store_tye = (($service_type == 1) ? 'service_type_1' : (($service_type == 2) ? 'service_type_2' : 'service_type_3'));
        $stores = Store::where('business_type_id', 3)->where('service_type', $store_tye)->get()->sortBy('name');
        if ($request->id == 1) {
            $categories = Category::where('parent_cat_id', 0)
                ->where('is_service', 1)
                ->where('service_type', 'service_type_1')
                ->pluck('name', 'id');
            return view('services.products.index', compact('stores', 'categories', 'service_type'));
        } elseif ($request->id == 2) {
            $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
                ->get()
                ->nest()
                ->setIndent('-->')
                ->listsFlattened('name');
            return view('services.products_2.index', compact('stores', 'categories', 'service_type'));
        } elseif ($request->id == 3) {
//            $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            //                ->get()
            //                ->nest()
            //                ->setIndent('-->')
            //                ->listsFlattened('name');
            return view('services.products_3.index', compact('stores', 'service_type'));
        } else {
            $categories = Category::where('parent_cat_id', 0)
                ->where('is_service', 1)
                ->where('service_type', 'service_type_1')
                ->pluck('name', 'id');
            return view('services.products.index', compact('stores', 'categories', 'service_type'));
        }
    }

    public function datatable(Request $request)
    {
        $currentUser = Auth::user();
        $products = ServiceProducts::with('mainCategory');
        $products->where('service_type', $request->service_type);
        if ($request->cat_id) {
            $products = $products->where('category_id', $request->cat_id);
        }
        $products->get()->sortBy('name');
        return Datatables::of($products)
            ->rawColumns(['actions'])

            ->editColumn('categories', function ($product) {
                return ($product->service_type != 3) ? $product->mainCategory->name : "";
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($products) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo("servicetype{$products->service_type}product_update")) {
                    $b .= '<a href="' . URL::route('admin.service-products.edit', $products->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo("servicetype{$products->service_type}product_delete")) {
                    $count = StoreServiceProducts::where('product_id', $products->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . URL::route('admin.service-products.destroy', $products->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.service-products.destroy', $products->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    }
                }
                if ($currentUser->hasPermissionTo("servicetype{$products->service_type}product_update")) {
                    $b .= ' <a href="#" class="btn btn-outline-info btn-xs store" data-toggle="modal" data-target="#storeModal" data-product-id="' . $products->id . '" data-product-name="' . $products->name . '"><i class="fa fa-shopping-bag"></i></a>';
                }
                return $b;
            })->make(true);
    }

    public function create(Request $request)
    {

        $this->authorize('create', [ServiceProducts::class, $request->id]);

        $businessTypeCatId = 1;
        $service_type = $request->id;
        if ($request->id == 1) {
            $sub_categories = [];
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            $categories = Category::where('parent_cat_id', 0)
                ->where('is_service', 1)
                ->where('service_type', 'service_type_1')
                ->pluck('name', 'id');
            return view('services.products.create', compact('service_type', 'imageSize', 'categories', 'businessTypeCatId', 'sub_categories'));
        } elseif ($request->id == 2) {
            $subCats = [];
            $serviceType2SubCategories = \DB::select("SELECT t1.id FROM categories AS t1 LEFT JOIN categories as t2 ON t1.id = t2.parent_cat_id WHERE t2.id IS NULL and t1.service_type='service_type_2'");
            if (!empty($serviceType2SubCategories)) {
                foreach ($serviceType2SubCategories as $k => $subid) {
                    $subCats[] = $subid->id;
                }
            }
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
                ->get()
                ->nest()
                ->setIndent('-->')
                ->listsFlattened('name');
            return view('services.products_2.create', compact('subCats', 'service_type', 'imageSize', 'categories', 'businessTypeCatId'));
        } else {
            $subCats = [];
            /*$serviceType2SubCategories = \DB::select("SELECT t1.id FROM categories AS t1 LEFT JOIN categories as t2 ON t1.id = t2.parent_cat_id WHERE t2.id IS NULL and t1.service_type='service_type_2'");
            if (!empty($serviceType2SubCategories)) {
            foreach ($serviceType2SubCategories as $k => $subid) {
            $subCats[] = $subid->id;
            }
            }*/
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            /*  $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            ->get()
            ->nest()
            ->setIndent('-->')
            ->listsFlattened('name');*/
            return view('services.products_3.create', compact('subCats', 'service_type', 'imageSize', 'businessTypeCatId'));
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', [ServiceProducts::class, $request->service_type]);

        $params = $request->except('_token');
        $request->validate([
            'name' => 'required',
            'unit_price' => 'required',
        ]);

        $service_type = $request->service_type;
        $image = "";
        $featured = $request->has('featured') ? 1 : 0;
        $status = $request->has('status') ? 1 : 0;
        $bring_material_charge = (!empty($request->bring_material_charge)) ? $request->bring_material_charge : 0;
        $category_id = ($service_type == 1 || $service_type == 2) ? $request->category_id : null;
        $by_user_id = auth()->user()->id;
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            $image = $this->singleImage($request->file('image'), $imageSize['path'], 'serviceProduct');
        }
        if ($service_type == 2) {
            $categories = Category::where('parent_cat_id', $request->category_id)->first();
            if (!empty($categories)) {
                return redirect()->back()->withErrors(['categories' => 'Sub category is required']);
            }
        }

        $ServiceProducts = ServiceProducts::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $category_id,
            'unit_price' => $request->unit_price,
            'max_person' => $request->max_person,
            'max_hour' => $request->max_hour,
            'bring_material_charge' => $bring_material_charge,
            'status' => $status,
            'image' => $image,
            'service_type' => $service_type,
            'featured' => $featured,
            'by_user_id' => $by_user_id,
        ]);

        alert()->success('Product added successfully.', 'Added');
        return redirect('admin/service-products?id=' . $service_type);
    }

    public function edit($id, Request $request)
    {

        $product = ServiceProducts::find($id);
        $this->authorize('update', [$product, $request->id]);

        $businessTypeCatId = 1;
        $imageSize = config('globalconstants.imageSize')['serviceProduct'];

        if ($product->service_type == 2) {
            $service_type = 2;
            $subCats = [];
            $serviceType2SubCategories = \DB::select("SELECT t1.id FROM categories AS t1 LEFT JOIN categories as t2 ON t1.id = t2.parent_cat_id WHERE t2.id IS NULL and t1.service_type='service_type_2'");
            if (!empty($serviceType2SubCategories)) {
                foreach ($serviceType2SubCategories as $k => $subid) {
                    $subCats[] = $subid->id;
                }
            }
            $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
                ->get()
                ->nest()
                ->setIndent('-->')
                ->listsFlattened('name');
            return view('services.products_2.edit', compact('subCats', 'service_type', 'categories', 'product', 'imageSize', 'businessTypeCatId'));
        } elseif ($product->service_type == 3) {
            $service_type = 3;
            $subCats = [];
//            $serviceType2SubCategories = \DB::select("SELECT t1.id FROM categories AS t1 LEFT JOIN categories as t2 ON t1.id = t2.parent_cat_id WHERE t2.id IS NULL and t1.service_type='service_type_2'");
            //            if (!empty($serviceType2SubCategories)) {
            //                foreach ($serviceType2SubCategories as $k => $subid) {
            //                    $subCats[] = $subid->id;
            //                }
            //            }
            //            $categories = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            //                ->get()
            //                ->nest()
            //                ->setIndent('-->')
            //                ->listsFlattened('name');
            return view('services.products_3.edit', compact('subCats', 'service_type', 'product', 'imageSize', 'businessTypeCatId'));
        } else {
            $service_type = 1;
            $categories = Category::where('parent_cat_id', 0)
                ->where('is_service', 1)
                ->where('service_type', 'service_type_1')
                ->pluck('name', 'id');
            return view('services.products.edit', compact('service_type', 'categories', 'product', 'imageSize', 'businessTypeCatId'));
        }
    }

    public function update(Request $request)
    {
        $ServiceProducts = ServiceProducts::find($request->id);
        $this->authorize('update', [$ServiceProducts, $request->id]);
        $params = $request->except('_token');
        $featured = $request->has('featured') ? 1 : 0;
        $status = $request->has('status') ? 1 : 0;
        $by_user_id = auth()->user()->id;
        $bring_material_charge = (!empty($request->bring_material_charge)) ? $request->bring_material_charge : 0;

        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            $ServiceProducts->image = $this->singleImage($request->file('image'), $imageSize['path'], 'serviceProduct');
        }
        $ServiceProducts->name = $request->name;
        $ServiceProducts->description = $request->description;
        $ServiceProducts->category_id = $request->category_id;
        $ServiceProducts->unit_price = $request->unit_price;
        $ServiceProducts->max_person = $request->max_person;
        $ServiceProducts->max_hour = $request->max_hour;
        $ServiceProducts->bring_material_charge = $bring_material_charge;
        $ServiceProducts->status = $status;
        $ServiceProducts->featured = $featured;
        $ServiceProducts->by_user_id = $by_user_id;
        $ServiceProducts->save();

        alert()->success('Product updated successfully.', 'Updated');
        return redirect('admin/service-products?id=' . $ServiceProducts->service_type);
        //return redirect()->route('admin.service-products.index',['id',$ServiceProducts->service_type]);
    }

    public function destroy($id, Request $request)
    {
        $ServiceProducts = ServiceProducts::find($id);
        $this->authorize('delete', [$ServiceProducts, $request->id]);
        $ServiceProducts->delete();
        return response()->json(["status" => true, "message" => 'Product deleted successfully']);
    }

    public function productStoreSave(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'store_id' => 'required',
        ]);
        $inputs = $request->all();
        $store_type = (($request->id == 1) ? 'service_type_1' : (($request->id == 2) ? 'service_type_2' : 'service_type_3'));
        $product = ServiceProducts::find($inputs['product_id']);
        if ($inputs['store_id'][0] === "all") {
            $stores = Store::where('business_type_id', 3)->where('service_type', $store_type)->pluck('id');
        } else {
            $stores = $inputs['store_id'];
        }
        foreach ($stores as $store) {
            $isExist = StoreServiceProducts::where('store_id', $store)->where('product_id', $product->id)->first();
            if (is_null($isExist)) {
                StoreServiceProducts::create([
                    'store_id' => $store,
                    'product_id' => $product->id,
                    'unit_price' => $product->unit_price,
                    'ask_price' => $product->unit_price,
                ]);
            }
        }
        alert()->success('Product Added to Stores Successfully', 'Added');
        return redirect()->route('admin.service-products.index', ['id' => $request->id]);
    }

    public function uploadImages(Request $request)
    {
        $product = ServiceProducts::find($request->id);
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['serviceProduct'];
            $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'serviceProduct');
        }
        $product->image = $request->image;
        $product->save();
    }

    public function deleteImages($id)
    {
        $product = ServiceProducts::find($id);
        if ($product->image != '') {
            $path = config('globalconstants.imageSize.serviceProduct')['path'] . '/';
            \Storage::delete($path . $product->image);
            $product->image = "";
        }
        alert()->success('Product Image Deleted Successfully', 'Deleted');
        return redirect()->route('admin.products.edit', $image->product_id);
    }

    public function fetchStoreProducts(Request $request)
    {
        $limit = $request->limit ?? 20;
        $request->validate([
            'store_id' => 'required',
            // 'category_id' => 'required'
        ]);
        $banners = ServiceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_product', 1)->where('status', 1)->get();

        $clearing_materials = ClearingMaterials::select('title','description')->get();

        if ($request->category_id) {
            $subcategory = Category::where('parent_cat_id', $request->category_id)->get();
            if (!$subcategory->isEmpty()) {
                return response()->json([
                    'statusCode' => 200,
                    'message' => trans('services.category.list'),
                    'data' => [
                        'sub_categories' => ServiceCategoryResource::collection($subcategory),
                        'banners' => $banners->isEmpty() ? null : new EcommerceBannerCollection($banners),
                    ],
                ]);
                // return new ServiceCategoryCollection($subcategory);
            } else {
                $products = StoreServiceProducts::where('store_id', $request->store_id)
                    ->whereHas('product', function ($query) use ($request) {
                        return $query->where('category_id', $request->category_id);
                    })
                    ->paginate($limit);
            }
        } else {
            if(isset($request->service_type) && $request->service_type == "service_type_3"){
                $products = StoreServiceProducts::where('store_id', $request->store_id)
                    ->whereHas('product', function ($query) use ($request) {
                        return $query->where('service_type', 3);
                    })
                    ->paginate($limit);
            }else{
                $products = StoreServiceProducts::where('store_id', $request->store_id)
                    ->has('product')
                    ->paginate($limit);
            }

        }
        return [
            'statusCode' => 200,
            'message' => trans('api.products.list'),
            'data' => [
                'products' => $products->isEmpty() ? null : ServiceStoreProductResource::collection($products),
                'banners' => $banners->isEmpty() ? null : new EcommerceBannerCollection($banners),
                'clearing_materials' => $clearing_materials->isEmpty() ? null : $clearing_materials,
            ],
            "links" => $products->isEmpty() ? null : [
                "first" => $products->url(1),
                "last" => $products->url($products->lastPage()),
                "prev" => $products->previousPageUrl(),
                "next" => $products->nextPageUrl(),
            ],
            "meta" => $products->isEmpty() ? null : [
                "current_page" => $products->currentPage(),
                "from" => $products->firstItem(),
                "last_page" => $products->lastPage(),
                "path" => null,
                "per_page" => $products->perPage(),
                "to" => $products->lastItem(),
                "total" => $products->total(),
            ],
        ];
    }

    public function fetchServiceProducts(Request $request)
    {
        $limit = $request->limit ?? 20;
        $request->validate([
            'store_id' => 'required',
            // 'category_id' => 'required'
        ]);
        $banners = ServiceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_product', 1)->where('status', 1)->get();

        if ($request->category_id) {
            $subcategory = Category::where('parent_cat_id', $request->category_id)->get();
            if (!$subcategory->isEmpty()) {
                return response()->json([
                    'statusCode' => 200,
                    'message' => trans('services.category.list'),
                    'data' => [
                        'sub_categories' => ServiceCategoryResource::collection($subcategory),
                        'banners' => $banners->isEmpty() ? null : new EcommerceBannerCollection($banners),
                    ],
                ]);
                // return new ServiceCategoryCollection($subcategory);
            } else {
                $products = StoreServiceProducts::where('store_id', $request->store_id)
                    ->whereHas('product', function ($query) use ($request) {
                        return $query->where('category_id', $request->category_id);
                    })
                    ->paginate($limit);
            }
        } else {
            $products = StoreServiceProducts::where('store_id', $request->store_id)
                ->has('product')
                ->paginate($limit);
        }
        return [
            'statusCode' => 200,
            'message' => trans('api.products.list'),
            'data' => [
                'products' => $products->isEmpty() ? null : ServiceStoreProductResource::collection($products),
                'banners' => $banners->isEmpty() ? null : new EcommerceBannerCollection($banners),
            ],
            "links" => $products->isEmpty() ? null : [
                "first" => $products->url(1),
                "last" => $products->url($products->lastPage()),
                "prev" => $products->previousPageUrl(),
                "next" => $products->nextPageUrl(),
            ],
            "meta" => $products->isEmpty() ? null : [
                "current_page" => $products->currentPage(),
                "from" => $products->firstItem(),
                "last_page" => $products->lastPage(),
                "path" => null,
                "per_page" => $products->perPage(),
                "to" => $products->lastItem(),
                "total" => $products->total(),
            ],
        ];
    }

    public function fetchSuggestedProducts(Request $request)
    {
        $limit = $request->limit ?? 5;
        $request->validate([
            'store_id' => 'required',
            'sub_category_id' => 'required',
        ]);
        // $products = StoreProduct::with(['product' => function($query) use ($request){
        //     return $query->whereIn('sub_category_id', $request->sub_category_id);
        // }])
        // ->where('store_id', $request->store_id)
        $products = StoreServiceProducts::where('store_id', $request->store_id)->whereHas('product', function ($query) use ($request) {
            return $query->whereIn('sub_category_id', (array) $request->sub_category_id);
        })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
        return new StoreProductCollection($products);
        // return successResponse(trans('api.products.list'), new StoreProductCollection($products));
        $products = StoreServiceProducts::where('store_id', $request->store_id)
            ->whereHas('product', function ($query) use ($request) {
                return $query->whereIn('category_id', (array) $request->category_id);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
        return new ServiceStoreProductCollection($products);
    }

    public function fetchInStores(Request $request)
    {
        $storeIds = StoreServiceProducts::where('product_id', $request->product_id)->get()->pluck('store_id');
        return $storeIds;
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'product-csv' => 'required|mimes:csv',
        // ]);
        $file = $request->file('products_csv');
        $import = new ServiceProductsImport;
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.service-products.index')->withSuccess($import->getTotalCount());
    }

    public function importServiceTypeTwo(Request $request)
    {
        // $request->validate([
        //     'product-csv' => 'required|mimes:csv',
        // ]);
        $file = $request->file('products_csv');
        $import = new ServiceTypeTwoProductsImport;
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.service-products.index', ['id' => 2])->withSuccess($import->getTotalCount());
    }
    public function importServiceTypeThree(Request $request)
    {
        // $request->validate([
        //     'product-csv' => 'required|mimes:csv',
        // ]);
        $file = $request->file('products_csv');
        $import = new ServiceTypeThreeProductsImport;
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.service-products.index', ['id' => 3])->withSuccess($import->getTotalCount());
    }
}
