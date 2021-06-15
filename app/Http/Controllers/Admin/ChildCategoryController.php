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

class ChildCategoryController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatable(Request $request)
    {
        $datas = Category::select('*')->where('parent_cat_id','!=', 0)->orderBy('name', 'asc');
        
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
                                ->editColumn('category', function(Category $data) {
                                    return $data->parent->name;
                                }) 
                                ->editColumn('subcategory', function(Category $data) {
                                    return $data->parent->name;
                                }) 
                            
                                ->editColumn('actions', function(Category $data) {
                                    $b = '<a href="' . URL::route('admin.childcategories.edit', $data->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                                
                                    $b .= ' <a href="' . URL::route('admin.childcategories.destroy', $data->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    
                                    return $b;
                                })->make(true); //--- Returning Json Data To Client Side
        }

    //*** GET Request
    public function index(){
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.childcategory.index',compact('cats'));
    }

    //*** GET Request
    public function create(){
        $cats = Category::where('parent_cat_id',0)
        ->orderBy('name', 'asc')->get();
        return view('admin.childcategory.create',compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
    
        //--- Logic Section
        $data = new Category();
        $input = $request->all();
        $data->fill($input)->save();
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
         return view('admin.childcategory.edit',compact('childcategory','cats'));
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
