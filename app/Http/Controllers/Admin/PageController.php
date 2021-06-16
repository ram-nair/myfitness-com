<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Page;
use App\Subscriber;
use App\Http\Controllers\Controller;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Validator;

class PageController extends BaseController
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatable(Request $request)
    {
         $currentUser = $request->user();
         $datas = Page::orderBy('id','desc')->get();
        
         return Datatables::of($datas)
                        ->rawColumns(['actions'])
                        ->editColumn('actions', function(Page $data) {
                            $b = '<a href="' . URL::route('admin.pages.edit', $data->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                        
                            $b .= ' <a href="' . URL::route('admin.pages.destroy', $data->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
            
                            return $b;
                        })->make(true); //--- Returning Json Data To Client Side

                         
                       
    }

    //*** GET Request
    public function index()
    {
        $this->setPageTitle('Content Page', 'Content Page');
        return view('admin.pages.index');
    }
    public function subscribers()
    {
        $this->setPageTitle('subscribers', 'subscribers');
        $sub = Subscriber::all();
        return view('admin.pages.list',compact('sub'));
    }
    
    //*** GET Request
    public function create()
    {
        $this->setPageTitle('Page', 'Create Content Page');
        $imageSize = config('globalconstants.imageSize')['product'];
        return view('admin.pages.create',compact('imageSize'));
    }

    public function show($id)
    {
        return redirect('pages');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home','faq','contact','blog','cart','checkout');
        if (in_array($slug, $main)) {
        return response()->json(array('errors' => [ 0 => 'This slug has already been taken.' ]));          
        }
        $rules = ['slug' => 'unique:pages'];
        $customs = ['slug.unique' => 'This slug has already been taken.'];
        $validator = Validator::make(Input::all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Page();
        $input = $request->all();
 
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
         }  
        if ($request->secheck == "") 
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;         
         } 
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit(Request $request, Page $pages)
    {
        $this->setPageTitle('Content Page', 'Edit Content Page');
        $imageSize = config('globalconstants.imageSize')['product'];
        return view('admin.pages.edit',compact('pages','imageSize'));
    }

    //*** POST Request
    public function update(Request $request, Page $page)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home','faq','contact','blog','cart','checkout');
        if (in_array($slug, $main)) {
        return response()->json(array('errors' => [ 0 => 'This slug has already been taken.' ]));          
        }
        $rules = ['slug' => 'unique:pages,slug,'.$id];
        $customs = ['slug.unique' => 'This slug has already been taken.'];
        $validator = Validator::make(Input::all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

           
        $input = $request->all();
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
         } 
         else {
            $input['meta_tag'] = null;
         }
        if ($request->secheck == "") 
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;         
         } 
        $page->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);    
        //--- Redirect Section Ends           
    }
      //*** GET Request Header
      public function header($id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->header = $id2;
            $data->update();
        }
      //*** GET Request Footer
      public function footer($id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->footer = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Page::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}