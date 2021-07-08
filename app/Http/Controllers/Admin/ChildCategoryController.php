<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\EcommerceBannerCollection;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class ChildCategoryController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatable(Request $request)
    {
        
        $datas = Category::select('*');
        
        if ($request->sub_cat_id) {
            $category_id = $request->sub_cat_id ;
            $datas->where('parent_cat_id', $category_id);
        }
        if ($request->parent_cat_id) {
            $category_id = $request->parent_cat_id ;
            $datas->where('parent_cat_id', $category_id);
        }
            $datas = $datas->orderBy('id','desc')->get();
            return Datatables::of($datas)
                                ->rawColumns(['actions'])
                                ->editColumn('category', function($datas) {
                                    return $datas->parent_cat_id == 0 ? $datas->name:$datas->parent->name;
                                }) 
                                ->editColumn('name', function($datas) {
                                    return $datas->parent_cat_id > 0 ? $datas->name . " (" . $datas->parent->name . ")" : $datas->name;
           
                                }) 
                            
                                ->editColumn('actions', function($datas) {
                                    $b = '<a href="' . URL::route('admin.childcategories.edit', $datas->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                                
                                    $b .= ' <a href="' . URL::route('admin.childcategories.destroy', $datas->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    
                                    return $b;
                                })->make(true); //--- Returning Json Data To Client Side
        }

    //*** GET Request
    public function index(Request $request)
    {
        $parent_id=$request->parent_cat_id ?? 0; 
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.childcategory.index',compact('cats','parent_id'));
    }

    //*** GET Request
    public function create(){
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->get();
        $imageSize = config('globalconstants.imageSize')['category'];
        return view('admin.childcategory.create',compact('cats','imageSize'));
    }

    //*** POST Request
    public function store(Request $request)
    {
    
        //--- Logic Section
       
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
        }
        if ($request->hasFile('banner_image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $request->banner_image = $this->singleImage($request->file('banner_image'), $imageSize['path'], 'category');
        }
        $category = Category::create([
            'name' => $request->name,
            'parent_cat_id' => $request->parent_cat_id,
            'banner_image' => $request->banner_image,
            'image' => $request->image,
           // 'status' => $request->status,
        ]);
        //--- Logic Section Ends

        alert()->success('Child Category successfully added.', 'Added');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.childcategories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.childcategories.index');
        } 
    }

    public function show(Category $childcategory)
    {
        return redirect('childcategories');
    }
    //*** GET Request
    public function edit(Category $childcategory)
    {
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->get();
        $imageSize = config('globalconstants.imageSize')['category'];
         return view('admin.childcategory.edit',compact('childcategory','cats','imageSize'));
    }

    //*** POST Request
    public function update(Request $request, Category $childcategory)
    {
        //--- Validation Section
       /* $rules = [
            'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make(Input::all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }*/
        //--- Validation Section Ends

        //--- Logic Section
      
        $input = $request->all();
        if ($request->hasFile('banner_images')) {
            $imageSize = config('globalconstants.imageSize')['category'];
                $input['banner_image'] = $this->singleImage($request->file('banner_images'), $imageSize['path'], 'category');
                if (!empty($input['banner_image'])) {
                    $path = config('globalconstants.imageSize.category')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $subcategory->getAttributes()['banner_image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $subcategory->banner_image);
                    }
                }
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
        $childcategory->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        alert()->success('Child Category details successfully updated.', 'Updated');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.childcategories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.childcategories.index');
        }    
        //--- Redirect Section Ends            
    }

      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Category::findOrFail($id1);
            $data->status = $id2;
            $data->update();
        }

    //*** GET Request
    public function load($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat'));
    }
    

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Category::findOrFail($id);

        if($data->products->count()>0)
        {
        //--- Redirect Section     
        $msg = 'Remove the products first !!!!';
        return response()->json($msg);      
        //--- Redirect Section Ends    
        }
        
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }


    public function loadSubcat($id, $sub)
    {
        $cat = Category::orderBy('name', 'ASC')->where('parent_cat_id',$id)->get();
        return view('admin.childcategory.subcat', compact('cat', 'sub'));
    }
    public function loadChildcat($id, $sub)
    {
        $cat = Category::orderBy('name', 'ASC')->where('parent_cat_id', $id)->get();
       return view('admin.childcategory.childcat', compact('cat', 'sub'));
    }
}
