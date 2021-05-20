<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\Store;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CategoryBannerController extends Controller
{
    use ImageTraits;

    public function __construct()
    {
        $this->authorizeResource(EcommerceBanner::class, 'cat_banner');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cat-banner.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $banner = EcommerceBanner::select('*');
        return Datatables::of($banner)
            ->rawColumns(['actions'])
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($banner) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('categorybanner_update')) {
                    $editUrl = \URL::route('admin.cat-banners.edit', $banner->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
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
        //Get all roles and pass it to the view
        $imageSize = config('globalconstants.imageSize')['categorybanner'];
        return view('admin.cat-banner.create', compact('stores', 'imageSize'));
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
            'name' => 'required|max:120',
        ]);

        $currentUser = $request->user();
        $imgPath = "";
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['categorybanner'];
            $request->image = $this->singleImage($request->file('images'), $imageSize['path'], 'categorybanner');
        }
        $banner = EcommerceBanner::create([
            'name' => $request->name,
            'image' => $request->image,
            'in_category' => ($request->in_category) ? 1 : 0,
            'in_product' => ($request->in_product) ? 1 : 0,
            'status' => ($request->status) ? 1 : 0,
            'url' => $request->url,
            'description' => $request->description,
            'by_user_id' => $currentUser->id,
        ]);
      /*  if (!empty($request->store_id)) {
            if ($request->store_id[0] == 'All') {
                $storeIds = Store::where('business_type_id', 1)->get()->pluck('id');
                $banner->store()->attach($storeIds);
            } else {
                $storeIds = $request->store_id;
                $banner->store()->attach($storeIds);
            }
        }*/

        //Redirect to the users.index view and display message
        alert()->success('Banner successfully added.', 'Added');
        return redirect()->route('admin.cat-banners.index');
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
    public function edit(EcommerceBanner $cat_banner)
    {
        $ecommerceBanner = EcommerceBanner::find($cat_banner->id);
        $imageSize = config('globalconstants.imageSize')['categorybanner'];
        return view('admin.cat-banner.edit', compact('ecommerceBanner', 'imageSize', 'stores')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EcommerceBanner $cat_banner)
    {
        $ecommerceBanner = EcommerceBanner::find($cat_banner->id);
        $this->validate($request, [
            'name' => 'required|max:120',
        ]);
        $input = $request->all();

        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['categorybanner'];
            $input['image'] = $this->singleImage($request->file('images'), $imageSize['path'], 'categorybanner');
            if (!empty($input['image'])) {
                $path = config('globalconstants.imageSize.banner')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $ecommerceBanner->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $ecommerceBanner->image);
                }
            }
        }
        $input['in_category'] = ($request->in_category) ? 1 : 0;
        $input['in_product'] = ($request->in_product) ? 1 : 0;
        $input['status'] = ($request->status) ? 1 : 0;
        $input['by_user_id'] = $request->user()->id;
        $ecommerceBanner->fill($input)->save();
       /* if (!empty($request->store_id)) {
            if ($request->store_id[0] == 'All') {
                $storeIds = Store::where('business_type_id', 1)->get()->pluck('id');
                $ecommerceBanner->store()->sync($storeIds);
            } else {
                $storeIds = $request->store_id;
                $ecommerceBanner->store()->sync($storeIds);
            }
        }*/

        alert()->success('Banner details successfully updated.', 'Updated');
        return redirect()->route('admin.cat-banners.index');
    }
}
