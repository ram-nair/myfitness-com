<?php

namespace App\Http\Controllers\Services;

use App\BusinessTypeCategory;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\EcommerceBannerCollection;
use App\Http\Resources\EcommerceBannerResource;
use App\Http\Resources\ServiceCategoryResource;
use App\ServiceBanner;
use App\ServiceProducts;
use App\Store;
use App\StoreServiceProducts;
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
        $this->authorizeResource(Category::class, 'service_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent_cat_id = $request->parent_cat_id ?? "";
        $parentCats = Category::where('parent_cat_id', 0)->where('is_service', 1)->pluck('name', 'id')->toArray();
        $imageSize = config('globalconstants.imageSize')['category'];
        return view('services.categories.index', compact('imageSize', 'parentCats', 'parent_cat_id'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $categories = Category::select('*')->where('is_service', 1);
        if ($request->parent_cat_id) {
            $catid = $request->parent_cat_id == "main" ? 0 : $request->parent_cat_id;
            $categories->where('business_type_category_id', $catid);
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
                    $b .= '<a href="' . URL::route('admin.service-categories.edit', $categories->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('category_delete')) {
                    $count = ServiceProducts::where('category_id', $categories->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . URL::route('admin.service-categories.destroy', $categories->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.service-categories.destroy', $categories->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $type_category = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            ->get()
            ->nest()
            ->setIndent('|-->')
            ->listsFlattened('name');

        $business_type_categories = BusinessTypeCategory::where('business_type_id', 3)->pluck('name', 'id');
        return view('services.categories.create', compact('type_category', 'business_type_categories', 'categories', 'imageSize'));
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
        $category = Category::create([
            'business_type_category_id' => $request->business_type_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'parent_cat_id' => $request->parent_cat_id,
            'image' => $request->image,
            'is_service' => 1,
            'service_type' => $request->service_type,

        ]);

        //Redirect to the users.index view and display message
        alert()->success('Category successfully added.', 'Added');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.service-categories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.service-categories.index');
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
    public function edit(Category $service_category)
    {
        $imageSize = config('globalconstants.imageSize')['category'];
        $categories = [];
        $type_category = Category::where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            ->get()
            ->nest()
            ->setIndent('|-->')
            ->listsFlattened('name');
        $business_type_categories = BusinessTypeCategory::where('business_type_id', 3)->pluck('name', 'id');
        return view('services.categories.edit', compact('type_category', 'service_category', 'categories', 'business_type_categories', 'imageSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $service_category)
    {
        $servicecategory = Category::find($service_category->id);
        $input = $request->only([
            'business_type_category_id',
            'name',
            'description',
            'parent_cat_id',
            'image',
            'is_service',
            'service_type',
        ]);

        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $input['image'] = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
            if (!empty($input['image'])) {
                $path = config('globalconstants.imageSize.category')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $servicecategory->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $servicecategory->image);
                }
            }
        }
        $input['is_service'] = 1;
        $servicecategory->fill($input)->save();

        alert()->success('Category details successfully updated.', 'Updated');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.service-categories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.service-categories.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $service_category)
    {
        if (Category::where('parent_cat_id', $service_category->id)->first()) {
            //category is parent cannot delete
            return response()->json(["status" => false, "message" => 'Cannot delete, this category has sub categories.']);
        }
        $service_category->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function getCategoriesOnBusinessTypeCategoryId(Request $request)
    {
        $categories = Category::where('business_type_category_id', $request->id)
            ->where('parent_cat_id', 0)
            ->orderBy('name', 'asc')->pluck('name', 'id');
        return successResponse(trans('api.success'), $categories);
    }

    public function fetchCategories(Request $request)
    {

        $request->validate([
            'store_id' => 'required',
            'service_type' => 'required',
            'business_type_cat_id' => 'required',
        ]);

        $banners = ServiceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_category', 1)->where('status', 1)->get();

        //get root level category only for servce type2
        if ($request->service_type == "service_type_2") {
            $product_ids = StoreServiceProducts::where('store_id', $request->store_id)->pluck('product_id');
            $category_ids = $result = false;
            if (!empty($product_ids)) {
                $category_ids = ServiceProducts::whereIn('id', $product_ids)
                    ->select('category_id')->groupBy('category_id')->pluck('category_id');
                $ancestory_cats = Category::select(\DB::raw("id, GetAncestry(id) as parents"))->whereIn('id', $category_ids)->get();
                $newCatIds = [];
                foreach ($ancestory_cats as $ancestor) {
                    if (!empty($ancestor['parents'])) {
                        $par = $ancestor['parents'];
                        $parent_levels = explode(",", $par);
                        $newCatIds[] = end($parent_levels);
                    } else {
                        $newCatIds[] = $ancestor['id'];
                    }
                }
                $category_ids = $newCatIds;
            }
            if ($category_ids) {
                $limit = $request->limit ?? 999;
                $result = Category::whereIn('id', $category_ids)->paginate($limit);
            }
        } else {
            $categories = Category::where('is_service', 1);
            if ($request->has('business_type_cat_id')) {
                $categories->where('business_type_category_id', $request->business_type_cat_id);
            }
            if ($request->has('service_type')) {
                $categories->where('service_type', $request->service_type);
            }
            if ($request->has('category_id')) {
                // $categories->where('parent_cat_id', "!=", 0);
                $categories->where('id', "!=", $request->category_id);
                $categories->where('parent_cat_id', $request->category_id);
            } else {
                $categories->where('parent_cat_id', 0);
            }
            $limit = $request->limit ?? 999;
            $result = $categories->paginate($limit);
        }

        return [
            'statusCode' => 200,
            'message' => trans('api.category.list'),
            'data' => [
                'categories' => empty($result) || $result->isEmpty() ? null : ServiceCategoryResource::collection($result),
                'banners' => empty($banners) || $banners->isEmpty() ? null : EcommerceBannerResource::collection($banners),
            ],
        ];
    }

    public function fetchStoreCategories(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        $banners = ServiceBanner::whereHas('store', function ($q) use ($request) {
            return $q->where('store_id', $request->store_id);
        })->where('in_category', 1)->where('status', 1)->get();

        $product_ids = StoreServiceProducts::where('store_id', $request->store_id)->pluck('product_id');
        $category_ids = false;
        $categories = [];
        if (!empty($product_ids)) {
            $category_ids = ServiceProducts::whereIn('id', $product_ids)
                ->select('category_id')->groupBy('category_id')->pluck('category_id');
        }
        if (!empty($category_ids)) {
            $categories = Category::whereIn('id', $category_ids)->get();
        }

        return [
            'statusCode' => 200,
            'message' => trans('api.category.list'),
            'data' => [
                'categories' => empty($categories) || $categories->isEmpty() ? null : ServiceCategoryResource::collection($categories),
                'banners' => $banners->isEmpty() ? null : new EcommerceBannerCollection($banners),
            ],
        ];
        //return new ServiceCategoryCollection($categories);
    }

    public function getServiceCategories(Request $request)
    {
        $categories = BusinessTypeCategory::where('business_type_id', 3)
            ->where('service_type', $request->id)
            ->orderBy('name', 'asc')->pluck('name', 'id');
        return successResponse(trans('api.success'), $categories);
    }

    public function getServiceSubCategories(Request $request)
    {
        $type_category = Category::where('business_type_category_id', $request->id)->where('is_service', 1)->where('service_type', 'service_type_2')->orderByRaw('-name ASC')
            ->get()
            ->nest()
            ->setIndent('|-->')
            ->listsFlattened('name');
        return successResponse(trans('api.success'), $type_category);
    }
}
