<?php

namespace App\Http\Controllers\Admin;

use App\Brand;
use App\Cart;
use App\Category;
use App\Contracts\ProductContract;
use App\EcommerceBanner;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreProductFormRequest;
use App\Http\Resources\EcommerceBannerCollection;
use App\Http\Resources\StoreProductCollection;
use App\Http\Resources\StoreProductResource;
use App\Imports\ProductsImport;
use App\Product;
use App\ProductImage;
use App\Store;
use App\StoreProduct;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class ProductController extends BaseController
{
    use ImageTraits;

    protected $productRepository;

    public function __construct(ProductContract $productRepository)
    {
        $this->authorizeResource(Product::class, 'product');
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $this->setPageTitle('Products', 'Products List');
        $categories = Category::where('parent_cat_id', 0)
            ->pluck('name', 'id');
             
        return view('admin.products.index', compact('categories'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $condition = [];
        if ($request->cat_id) {
            $condition['category_id'] = $request->cat_id;
        }
        if ($request->sub_cat_id) {
            $condition['sub_category_id'] = $request->sub_cat_id;
        }
        if ($request->child_id) {
            $condition['child_category_id'] = $request->child_id;
        }
        if (!empty($condition)) {
            $products = $this->productRepository->findBy($condition);
        } else {
            $products = $this->productRepository->listProducts('created_at');
        }

        return Datatables::of($products)
            // ->orderColumn('created_at', '-created_at $1')
            ->rawColumns(['actions'])
            ->editColumn('brand_id', function ($product) {
                return $product->brand ? $product->brand->name : "-";
            })
            ->editColumn('categories', function ($product) {
                if ($product->subCategory) {
                    return $product->mainCategory->name . " - " . $product->subCategory->name;
                } else {
                    return $product->mainCategory->name;
                }
            })
            ->editColumn('quantity', function ($product) {
                return $product->quantity . ' ' . $product->unit ?? ' ';
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($products) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('ecomproduct_update')) {
                    $b .= '<a href="' . URL::route('admin.products.edit', $products->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('ecomproduct_delete')) {
                    $count = StoreProduct::where('product_id', $products->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . URL::route('admin.products.destroy', $products->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.products.destroy', $products->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    }
                }
                /*if ($currentUser->hasPermissionTo('storeproduct_create')) {
                    $b .= ' <a href="#" class="btn btn-outline-info btn-xs store" data-toggle="modal" data-target="#storeModal" data-product-id="' . $products->id . '" data-product-name="' . $products->name . '"><i class="fa fa-shopping-bag"></i></a>';
                }*/
                return $b;
            })->make(true);
    }

    public function show(Product $product)
    {
        return redirect('products');
    }
    public function create()
    {
       
        $brands = Brand::all()->sortBy('name');
        $categories = Category::where('parent_cat_id', 0)
        ->pluck('name', 'id');
        $sub_categories = [];
        $imageSize = config('globalconstants.imageSize')['product'];
        $this->setPageTitle('Products', 'Create Product');
        return view('admin.products.create', compact('imageSize','categories', 'brands', 'sub_categories'));
    }

    public function store(Request $request)
    {
        $params = $request->except('_token');
        $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products,sku',
            'unit_price' => 'required',
        ]);
        $product = $this->productRepository->createProduct($params);

        $this->uploadImages($request,$product);      
        if (!$product) {
            alert()->error('Product add failed', 'Failed');
            return $this->responseRedirectBack('Error occurred while creating product.', 'error', true, true);
        }
        alert()->success('Product added successfully.', 'Added');
        return redirect()->route('admin.products.edit', ['product' => $product->id, 'upload' => true]);
    }

    public function edit(Request $request, Product $product)
    {
        $showUpload = $request->upload ?? false;
       
        $product = $this->productRepository->findProductById($product->id);
        $imageSize = config('globalconstants.imageSize')['product'];
        $brands = Brand::all()->sortBy('name');
        // $categories = Category::all()->sortBy('name');
        $categories = Category::where('parent_cat_id', 0)
            ->pluck('name', 'id');
        $sub_categories = Category::where('parent_cat_id', $product->category_id)
            ->pluck('name', 'id');
        $this->setPageTitle('Products', 'Edit Product');
        return view('admin.products.edit', compact('categories', 'brands', 'product', 'imageSize', 'sub_categories', 'showUpload'));
    }

    public function update(Request $request, Product $product)
    {
        $params = $request->except('_token');
        $request->validate([
            'name' => 'required',
            'unit_price' => 'required',
        ]);
        $product = $this->productRepository->updateProduct($params);
        $this->uploadImages($request,$product); 
        if (!$product) {
            return $this->responseRedirectBack('Error occurred while updating product.', 'error', true, true);
        }
        alert()->success('Product updated successfully.', 'Updated');
        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product = $this->productRepository->deleteProduct($product->id);
        if (!$product) {
            return $this->responseRedirectBack('Error occurred while deleting product.', 'error', true, true);
        }
        return response()->json(["status" => true, "message" => 'Product deleted successfully']);
    }

    public function productStoreSave(Request $request)
    {
        $inputs = $request->all();
        $product = $this->productRepository->findProductById($inputs['id']);
        if ($inputs['store_id'][0] === "all") {
            $stores = Store::where('business_type_id', 1)->get()->pluck('id');
        } else {
            $stores = $inputs['store_id'];
        }
        foreach ($stores as $store) {
            $isExist = StoreProduct::where('store_id', $store)->where('product_id', $product->id)->first();
            if (is_null($isExist)) {
                StoreProduct::create([
                    'store_id' => $store,
                    'product_id' => $product->id,
                    'unit_price' => $product->unit_price,
                    'ask_price' => $product->unit_price,
                    'stock' => $request->stock,
                    'quantity_per_person' => $request->quantity_per_person,
                ]);
            }
        }
        alert()->success('Product Added to Stores Successfully', 'Added');
        return redirect()->route('admin.products.index');
    }

   /* public function uploadImages(Request $request)
    {
        $product = $this->productRepository->findProductById($request->id);
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['product'];
            $image = $this->singleImage($request->file('image'), $imageSize['path'], 'product');
            if (!empty($image)) {
                $productImage = new ProductImage([
                    'full' => $image,
                ]);
                $product->images()->save($productImage);
            }
        }
    }*/

    public function uploadImages(Request $request,$product)
    {
        
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['product'];
            $image = $request->file('image');
            foreach ($image as $files) {
                $image = $this->singleImage($files, $imageSize['path'], 'product');
                if (!empty($image)) {
                    $productImage = new ProductImage([
                        'full' => $image,
                    ]);
                    $product->images()->save($productImage);
                }
         
            }
            }
    }


    public function deleteImages($id)
    {
        $image = ProductImage::findOrFail($id);
        if ($image->full != '') {
            $path = config('globalconstants.imageSize.product')['path'] . '/';
            \Storage::delete($path . $image->full);
            $image->delete();
        }
        alert()->success('Product Image Deleted Successfully', 'Deleted');
        return redirect()->route('admin.products.edit', $image->product_id);
    }

    public function fetchStoreProducts(Request $request)
    {
        $limit = $request->limit ?? 20;
        $request->validate([
            'store_id' => 'required',
        ]);

        //banner add here
        $banners = EcommerceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_product', 1)->where('status', 1)->get();

        if ($request->category_id) {
            $products = StoreProduct::where('store_id', $request->store_id)->whereHas('product', function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })->paginate($limit);
        } else if ($request->sub_category_id) {
            $products = StoreProduct::where('store_id', $request->store_id)->whereHas('product', function ($query) use ($request) {
                return $query->where('sub_category_id', $request->sub_category_id);
            })->paginate($limit);
        } else {
            $products = StoreProduct::where('store_id', $request->store_id)
                ->has('product')->paginate($limit);
        }
        return [
            'statusCode' => 200,
            'message' => trans('api.products.list'),
            'data' => [
                'products' => $products->isEmpty() ? null : StoreProductResource::collection($products),
                'banners' => !$banners->isEmpty() ? new EcommerceBannerCollection($banners) : null,
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

        $productIds = Cart::where('store_id', $request->store_id)
            ->where('user_id', $request->user()->id)
            ->get()->pluck('product_id');
        $products = StoreProduct::where('store_id', $request->store_id)
            ->where('stock', '>=', 1)
            ->where('out_of_stock', 0)
            ->whereHas('product', function ($query) use ($request) {
                $query->whereIn('sub_category_id', $request->sub_category_id);
            });
        if (!empty($productIds)) {
            $products->whereNotIn('id', $productIds);
        }
        $result = $products->inRandomOrder()->limit($limit)->get();
        return new StoreProductCollection($result);
    }

    public function fetchInStores(Request $request)
    {
        $storeIds = StoreProduct::where('product_id', $request->product_id)->get()->pluck('store_id');
        return $storeIds;
    }

    public function updateHome($id)
    {
        $products =Product::find($id);
        if($products->hot_sale){
            $products->hot_sale=0;
        }else{
            $products->hot_sale=1;
        }

         $products->save();
         return true;
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'product_csv' => 'required|mimes:csv',
        // ]);
        $file = $request->file('products_csv');
        $import = new ProductsImport;
        $import->import($file);
        if (!$import->failures()->isEmpty()) {
            return back()->withFailures($import->failures())->withSuccess($import->getTotalCount());
        }
        alert()->success('Products detail added successfully.', 'Added');
        return redirect()->route('admin.products.index')->withSuccess($import->getTotalCount());
    }

    public function searchProducts(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'keyword' => 'required',
        ]);
        $limit = $request->limit ?? 20;
        $query = StoreProduct::where('store_id', $request->store_id);
        if ($request->exclude_outofstock) {
            $query->where('out_of_stock', 0)
                ->where('stock', ">", 0);
        }
        $products = $query->whereHas('product', function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->keyword . '%');
        })->paginate($limit);
        return new StoreProductCollection($products);
    }
 //27/05/2021

 public function offer_price(Request $request)
    {
        $product = $this->productRepository->findProductById($request->id);
        $params = $request->except('_token');
        $request->validate([
            'discount_price' => 'required',
            'discount_start_date' => 'required',
            'discount_end_date' => 'required',
        ]);
        $product->discount_price =$request->discount_price;
        $product->discount_start_date =$request->discount_start_date;
        $product->discount_end_date =$request->discount_end_date;
        $product->save();
        return redirect()->route('admin.products.edit', ['product' => $product->id]);
 
    }




}
