<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Category;
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


class SubCategoryController extends Controller
{
    use ImageTraits;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatable(Request $request)
    {
        $datas = Category::select('*')->where('parent_cat_id','!=',0);
        
         //--- Integrating This Collection Into Datatables

         if ($request->parent_cat_id) {
             $category_id = $request->parent_cat_id ;
             $datas->where('parent_cat_id', $category_id);
         }
        $datas = $datas->orderBy('id','desc')->get();
         return Datatables::of($datas)
                         ->rawColumns(['actions'])
                            ->addColumn('category', function($datas) {
                                return $datas->parent_cat_id == 0 ? $datas->parent->name:$datas->parent->name;
                            }) 
                            ->addColumn('name', function($datas) {
                                return $datas->parent_cat_id > 0 ? $datas->name . " (" . $datas->parent->name . ")" : $datas->name;
                            }) 
                           /* ->addColumn('status', function(Subcategory $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><<option data-val="0" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
                            }) */
                            ->editColumn('actions', function($datas) {
                                $b = '<a href="' . URL::route('admin.subcategories.edit', $datas->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                                $b .= ' <a href="' . URL::route('admin.subcategories.destroy', $datas->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                                return $b;
                              //  return '<div class="action-list"><a data-href="' . route('admin-subcat-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-subcat-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })->make(true);
    }

    //*** GET Request
    public function index(Request $request)
    {
        $parent_id=$request->parent_cat_id ?? 0; 
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.subcategory.index',compact('cats','parent_id'));
    }

    //*** GET Request
    public function create()
    {
        $imageSize = config('globalconstants.imageSize')['category'];
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.subcategory.create',compact('cats','imageSize'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
      /*  $rules = [
            'slug' => 'unique:subcategories|regex:/^[a-zA-Z0-9\s-]+$/'
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
     

        alert()->success('Sub Category successfully added.', 'Added');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.subcategories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.subcategories.index');
        } 
        //--- Redirect Section Ends    
    }

    public function show(Category $subcategory)
    {
        return redirect('subcategories');
    }


    //*** GET Request
    public function edit(Category $subcategory)
    {
    	$cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->pluck('name', 'id');
         $imageSize = config('globalconstants.imageSize')['category'];
        return view('admin.subcategory.edit',compact('subcategory','cats','imageSize'));
    }

    //*** POST Request
    public function update(Request $request, Category $subcategory)
    {
        $input = $request->only([
            'name',
            'parent_cat_id',
            'status',
            'banner_image'
        ]);
        
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
     
      
        $subcategory->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        alert()->success('Sub Category successfully updated.', 'Updated');
        if ($request->parent_cat_id > 0) {
            return redirect()->route('admin.subcategories.index', ['parent_cat_id' => $request->parent_cat_id]);
        } else {
            return redirect()->route('admin.subcategories.index');
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
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat'));
    }
    
     public function loadsub($id)
    {
        $cat = Category::where('parent_cat_id', $id)->orderBy('name', 'asc')->get();
      //  print_r( $subcat);die;
        return view('load.subcategory',compact('cat'));
    }
    
    //*** GET Request Delete
    public function destroy(Category $category)
    {

        if($category->childs->count()>0)
        {
        //--- Redirect Section     
        $msg = 'Remove the subcategories first !!!!';
        return response()->json($msg);      
        //--- Redirect Section Ends    
        }
        if($category->products->count()>0)
        {
        //--- Redirect Section     
        $msg = 'Remove the products first !!!!';
        return response()->json($msg);      
        //--- Redirect Section Ends    
        }

       
        $category->delete();
        //--- Redirect Section     
        alert()->success('Contents Deleted successfully.', 'Deleted');
        return redirect()->route('admin.subcategories.index');      
        //--- Redirect Section Ends   
    }
    
}
