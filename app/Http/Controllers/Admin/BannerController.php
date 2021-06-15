<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Banner;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerCollection;
use App\Store;
use App\Traits\ImageTraits;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class BannerController extends Controller
{
    use ImageTraits;

    public function __construct()
    {
        $this->authorizeResource(Banner::class, 'banner');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.banners.index');
    }

    public function datatable()
    {
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->hasRole('super-admin', 'admin');

        $banner = Banner::select('*');
        return Datatables::of($banner)
            ->setRowClass(function ($banner) {
                return !$banner->status ? 'alert-warning' : '';
            })
            ->rawColumns(['actions'])
            ->editColumn('image', function ($banner) {
                $b = '<img src="' .$banner->image . '" class="btn btn-outline-primary btn-xs"/>';
              return $b;
            })
            ->editColumn('status', function ($banner) {
                return $banner->status == 1 ? "Enabled" : "Disabled";
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($banner) use ($currentUser, $isSuperAdmin) {
                $b = '';
                if ($isSuperAdmin || $currentUser->hasPermissionTo('banner_update', 'admin')) {
                    $b .= '<a href="' . URL::route('admin.banners.edit', $banner->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }

                // if ($isSuperAdmin || $currentUser->hasPermissionTo('banner_delete', 'admin')) {
                //     $b .= ' <a href="' . URL::route('admin.banners.destroy', $banner->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                // }

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
        //Get all roles and pass it to the view
        $imageSize = config('globalconstants.imageSize')['banner1'];
        
        return view('admin.banners.create', compact('imageSize'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'images' => 'required'
        ]);
        $imgPath = "";
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['banner1'];
            $request->image = $this->singleImage($request->file('images'), $imageSize['path'], 'banner');
        }

        $banner = Banner::create([
            'name' => $request->name ?? "",
            'image' => $request->image,
            'url' => $request->url?? "",
            'status' => $request->status == 1 ? 1 : 0,
            'description' => $request->description?? "",
            'by_user_id' => $request->user()->id,
        ]);

        //Redirect to the users.index view and display message
        alert()->success('Banner successfully added.', 'Added');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('banner');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $stores = Store::all();
        $imageSize = config('globalconstants.imageSize')['banner'];
        return view('admin.banners.edit', compact('banner', 'imageSize', 'stores')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'name' => 'required|max:120',
        ]);
        $input = $request->all();
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['banner'];
            $input['image'] = $this->singleImage($request->file('images'), $imageSize['path'], 'banner');
            if (!empty($input['image'])) {
                $path = config('globalconstants.imageSize.banner')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $banner->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $banner->image);
                }
            }
        }
        $input['status'] = $request->status ?? 0;
      
        $banner->fill($input)->save();

        alert()->success('Banner details successfully updated.', 'Updated');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Banner $banner)
    // {
    //     $status = ($banner->status == 1) ? 0 : 1;
    //     $banner->status = $status;
    //     $banner->save();
    //     // Banner::destroy($id);
    //     alert()->success('Successfully Disabled.', 'Updated');
    //     return redirect()->route('admin.banners.index');
    // }

    //API FOR BANNER
    public function fetchHomeBanners(Request $request)
    {
        $banners = Banner::where('status', 1);

        if ($request->service_type == 1) {
            $banners->where('in_service_1', 1);
        } else if ($request->service_type == 2) {
            $banners->where('in_service_2', 1);
        } else if ($request->service_type == 3) {
            $banners->where('in_service_3', 1);
        } else if ($request->class == 1) {
            $banners->where('in_classes', 1);
        } else {
            $banners->where('in_ecommerce', 1);
        }

        $limit = $request->limit ?? 999;
        $result = $banners->skip(0)->take($limit)->get();
        return new BannerCollection($result);
    }
}
