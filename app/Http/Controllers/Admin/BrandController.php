<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandStoreRequest;
use App\Product;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;
use App\Audit;

class BrandController extends Controller
{
    use ImageTraits;

    public function __construct()
    {
        $this->authorizeResource(Brand::class, 'brand');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.brands.index');
    }

    public function datatable()
    {
        $currentUser = Auth::user();
        $brands = $brands = Brand::all()->sortBy('name');
        return Datatables::of($brands)
            ->rawColumns(['actions'])
            ->editColumn('created_at', function ($brands) {
                return $brands->created_at->format('F d, Y h:ia');
            })
            ->editColumn('actions', function ($brands) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('brand_update')) {
                    $b .= '<a href="' . URL::route('admin.brands.edit', $brands->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('brand_delete')) {
                    $count = Product::where('brand_id', $brands->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . URL::route('admin.brands.destroy', $brands->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.brands.destroy', $brands->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $imageSize = config('globalconstants.imageSize')['brand'];
        return view('admin.brands.create', compact('imageSize'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandStoreRequest $request)
    {
        $currentUser = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['brand'];
            $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'brand');
        }
        $brand = Brand::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'status' => $request->status == 1 ? 1 : 0,
        ]);

        //Redirect to the users.index view and display message
        alert()->success('Brand successfully added.', 'Added');
        return redirect()->route('admin.brands.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return redirect('brands');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $imageSize = config('globalconstants.imageSize')['brand'];
        return view('admin.brands.edit', compact('brand', 'imageSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $input = $request->only(['name', 'image', 'description']);
        $imgPath = "";
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['brand'];
            $input['image'] = $this->singleImage($request->file('image'), $imageSize['path'], 'brand');
            if (!empty($input['image'])) {
                $path = config('globalconstants.imageSize.brand')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $brand->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $brand->image);
                }
            }
        }

        $brand->fill($input)->save();

        alert()->success('Brand details successfully updated.', 'Updated');
        return redirect()->route('admin.brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditlist()
    {
       $users = Admin::all();
       $brands = Brand::all();
       
        return view('admin.brands.auditlist',compact('users','brands'));
            
        
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditdatatable(Request $request)
    {

         /*$users = Admin::with('audits')->where('id', $id)->first();*/
       /*  $user = Admin::findOrFail($id);*/
        
       
        $audits = Audit::with('brand')->where('auditable_type','App\\Brand')->orderBy('created_at','desc');

        if ($request->user_id && !empty($request->user_id)) {
            
           
            $audits->where('admin_id', $request->user_id);
        }
        if ($request->event_id && !empty($request->event_id)) {
            
           
            $audits->where('event', $request->event_id);
        }
         if ($request->brand_id && !empty($request->brand_id)) {
            
           
            $audits->where('auditable_id', $request->brand_id);
        }

         return Datatables::of($audits)->editColumn('created_at', function ($audit) {
                return $audit->created_at->format('F d, Y h:ia');
            })->make(true);
            
        
    }

}
