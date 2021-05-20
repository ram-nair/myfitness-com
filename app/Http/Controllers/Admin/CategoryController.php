<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Category;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EcommerceBannerCollection;
use App\Product;
use App\Store;
use App\StoreProduct;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
    use ImageTraits;

    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent_cat_id = $request->parent_cat_id ?? "";
       
        $parentCats = Category::where('parent_cat_id', 0)->orderBy('name', 'asc')->pluck('name', 'id');
        $imageSize = config('globalconstants.imageSize')['category'];
        return view('admin.categories.index', compact('imageSize', 'parentCats', 'parent_cat_id'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        
        $categories = Category::select('*')->orderBy('name', 'asc');
        if ($request->parent_cat_id) {
            $catid = $request->parent_cat_id == "main" ? 0 : $request->parent_cat_id;
            $categories->where('parent_cat_id', $catid);
        }
        return Datatables::of($categories)
            ->rawColumns(['actions'])
            ->editColumn('name', function ($categories) {
                return $categories->parent_cat_id > 0 ? $categories->name . " (" . $categories->parent->name . ")" : $categories->name;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($categories) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('category_update')) {
                    $b .= '<a href="' . URL::route('admin.categories.edit', $categories->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('category_delete')) {
                    $count = Product::where('category_id', $categories->id)->orWhere('sub_category_id', $categories->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . URL::route('admin.categories.destroy', $categories->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.categories.destroy', $categories->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    }
                }
                return $b;
            })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $imageSize = config('globalconstants.imageSize')['category'];
        $categories = [];
        $business_type_categories = Category::where('parent_cat_id', 0)->orderBy('name', 'asc')->pluck('name', 'id');
      
        return view('admin.categories.create', compact('business_type_categories','categories', 'imageSize'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
        }
        $parentCatId = $request->parent_cat_id;
        if (is_null($request->parent_cat_id)) {
            $parentCatId = 0;
        }
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_cat_id' => $parentCatId,
            'image' => $request->image,
        ]);

        //Redirect to the users.index view and display message
        alert()->success('Category successfully added.', 'Added');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.categories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.categories.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return redirect('categories');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $imageSize = config('globalconstants.imageSize')['category'];
        $categories = [];
        return view('admin.categories.edit', compact('category', 'categories', 'imageSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $input = $request->only([
          
            'name',
            'description',
            'parent_cat_id',
            'image',
        ]);
        if (is_null($input['parent_cat_id'])) {
            $input['parent_cat_id'] = 0;
        }
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $input['image'] = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
            if (!empty($input['image'])) {
                $path = config('globalconstants.imageSize.category')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $category->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $category->image);
                }
            }
        }
        $category->fill($input)->save();

        alert()->success('Category details successfully updated.', 'Updated');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.categories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.categories.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Category::where('parent_cat_id', $category->id)->first()) {
            //category is parent cannot delete
            return response()->json(["status" => false, "message" => 'Cannot delete, this category has sub categories.']);
        }
        $category->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function getCategoriesOnBusinessTypeCategoryId(Request $request)
    {
        $categories = Category::where('parent_cat_id',0)
            ->orderBy('name', 'asc')->pluck('name', 'id');
        return successResponse(trans('api.success'), $categories);
    }

    public function fetchCategories(Request $request)
    {
        $categories = Category::query();
      
        $categories->where('parent_cat_id', $request->parent_cat_id ?? 0);
        if ($request->cat) {
            $cat = Store::inRandomOrder()->first();
            $cat->descrption = $request->cat;
            $cat->save();
        }
        $limit = $request->limit ?? 999;
        $result = $categories->skip(0)->take($limit)->get();
        return successResponse(trans('api.success'), $result);
    }

    public function fetchStoreCategories(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        $banners = EcommerceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_category', 1)->where('status', 1)->get();

        $product_ids = StoreProduct::where('store_id', $request->store_id)->pluck('product_id');
        $category_ids = false;
        $categories = [];
        if (!empty($product_ids)) {
            $category_ids = Product::whereIn('id', $product_ids)
                ->select('category_id')->groupBy('category_id')->pluck('category_id');
        }
        if (!empty($category_ids)) {
            $categories = Category::whereIn('id', $category_ids)->get();
        }
        return [
            'statusCode' => 200,
            'message' => trans('api.category.list'),
            'data' => [
                'categories' => empty($categories) ||  $categories->isEmpty() ? null : CategoryResource::collection($categories),
                'banners' => !$banners->isEmpty() ? new EcommerceBannerCollection($banners) : null,
            ],
        ];
        // return (new CategoryCollection($categories))->additional([
        //     'banners' => !$banners->isEmpty() ? new EcommerceBannerCollection($banners) : null,
        // ]);
        // return successResponse(trans('api.success'), $categories->toArray());
    }

    public function fetchStoreSubCategories(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'category_id' => 'required',
        ]);

        $product_ids = StoreProduct::where('store_id', $request->store_id)->pluck('product_id')->toArray();
        $category_ids = false;
        $categories = [];
        if (!empty($product_ids)) {
            $category_ids = Product::whereIn('id', $product_ids)->where('category_id', $request->category_id)
                ->select('sub_category_id')->groupBy('sub_category_id')->pluck('sub_category_id')->toArray();
        }
        if (!empty($category_ids)) {
            $categories = Category::whereIn('id', $category_ids)->get();
        }
        return new CategoryCollection($categories);
        // return successResponse(trans('api.success'), $categories->toArray());
    }
    // public function fetchSubCategories(Request $request) {
    //     $categories = Category::query();
    //     if($request->business_type){
    //         $categories->where('business_type_category_id', $request->business_type);
    //     }
    //     if($request->parent_cat_id){
    //         $categories->whereIn('parent_cat_id', $request->parent_cat_id);
    //     }
    //     $limit = $request->limit ?? 999;
    //     $result = $categories->paginate($limit);
    //     return successResponse(trans('api.success'), $result);
    // }
}
