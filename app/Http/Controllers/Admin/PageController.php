<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Page;
use App\Contact;
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
                        ->editColumn('actions', function(Page $page) {
                            $b = '<a href="' . URL::route('admin.pages.edit', $page->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                           // $b .= ' <a href="' . URL::route('admin.pages.destroy', $page->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $this->setPageTitle('Subscribers List', 'Subscribers List');
        return view('admin.pages.list');
    }
    public function list(Request $request)
    {
         $currentUser = $request->user();
         $datas = Subscriber::orderBy('id','desc')->get();
        
         return Datatables::of($datas)
                        ->rawColumns(['actions'])
                        ->editColumn('actions', function(Subscriber $data) {
                            $b = ' <a href="' . URL::route('admin.pages.destroy', $data->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
            
                            return $b;
                        })->make(true); //--- Returning Json Data To Client Side

                         
                       
    }



    //enquiry list

    public function enquiry()
    {
        $this->setPageTitle('Contact/Enquiry', 'Contact/Enquiry List');
        return view('admin.pages.enlist');
    }
    public function enqlist(Request $request)
    {
         $currentUser = $request->user();
         $datas = Contact::orderBy('id','desc')->get();
        
         return Datatables::of($datas)
                        ->rawColumns(['actions'])
                        ->editColumn('actions', function(Contact $data) {
                            $b = ' <a href="' . URL::route('admin.pages.destroys', $data->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
            
                            return $b;
                        })->make(true); //--- Returning Json Data To Client Side

                         
                       
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
       
        $data = new Page();
        $input = $request->all();
        if ($request->hasFile('banner_image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
            $input['image'] = $this->singleImage($request->file('banner_image'), $imageSize['path'], 'category');
                
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        alert()->success('Contents Added successfully.', 'Added');
        return redirect()->route('admin.pages.index');     
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit(Request $request, Page $page)
    {
        $this->setPageTitle('Content Page', 'Edit Content Page');
        $imageSize = config('globalconstants.imageSize')['product'];
        return view('admin.pages.edit',compact('page','imageSize'));
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
        $rules = ['slug' => 'unique:pages,slug,'.$page->id];
        $customs = ['slug.unique' => 'This slug has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

           
        $input = $request->all();
        if ($request->hasFile('banner_image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
                $input['image'] = $this->singleImage($request->file('banner_image'), $imageSize['path'], 'category');
                if (!empty($input['image'])) {
                    $path = config('globalconstants.imageSize.category')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $category->getAttributes()['image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $page->image);
                    }
                }
        }
        $page->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        alert()->success('Contents updated successfully.', 'Updated');
        return redirect()->route('admin.pages.index');  
        //--- Redirect Section Ends           
    }
      //*** GET Request Header
    

    //*** GET Request Delete
    public function destroy(Page $page)
    {
       
        $page->delete();
        //--- Redirect Section     
       alert()->success('Contents Deleted successfully.', 'Deleted');
        return redirect()->route('admin.pages.index');      
        //--- Redirect Section Ends   
    }
    public function destroys(Contact $contact)
    {
       
        $contact->delete();
        //--- Redirect Section     
       alert()->success('Contents Deleted successfully.', 'Deleted');
        return redirect()->route('admin.pages.enquiry');      
        //--- Redirect Section Ends   
    }
}