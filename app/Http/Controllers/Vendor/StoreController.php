<?php

namespace App\Http\Controllers\Vendor;

use App\BusinessType;
use App\BusinessTypeCategory;
use App\Http\Controllers\Controller;
use App\Store;
use App\Traits\ImageTraits;
use App\Vendor;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class StoreController extends Controller
{
    use ImageTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frond.stores.index');
    }
    public function datatable()
    {
        $currentUser = Auth::user();
        $stores = Store::select('*');
        return Datatables::of($stores)
            ->rawColumns(['actions'])
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($user) use ($currentUser) {
            $b = '';
            if ($currentUser->hasPermissionTo('stores_update')) {
                $b .= '<a href="' . URL::route('frond.stores.edit', $user->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
            }
            if ($currentUser->hasPermissionTo('stores_delete')) {
                $b .= ' <a href="' . URL::route('frond.stores.destroy', $user->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $vendors = Vendor::all()->where('active', 1)->sortBy('name');
        $businessType = BusinessType::all()->sortBy('name');
        $imageSize = config('globalconstants.imageSize')['store'];

        return view('frond.stores.create', compact('vendors', 'businessType', 'imageSize'));
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
        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:stores',
            'mobile' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['store'];
            $request->image = $this->singleImage($request->file('images'), $imageSize['path'], 'store');
        }

        //  $request->merge(['type' => 'admin', 'admin_id' => Auth::guard('admin')->user()->id]);
        //$request->images=$imgPath;
        $request->credit_card = ($request->credit_card) ? 1 : 0;
        $request->featured = ($request->featured) ? 1 : 0;
        $request->bring_card = ($request->bring_card) ? 1 : 0;
        $request->cash_accept = ($request->cash_accept) ? 1 : 0;
        $request->accuracy = ($request->accuracy) ? 1 : 0;
        $request->speed = ($request->speed) ? 1 : 0;

        $user = Store::create([
            'type_id' => $request->type_id,
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'active' => $request->active,
            'image' => $request->image,
            'mobile' => $request->mobile,
            'description' => $request->description,
            'store_timing' => $request->store_timing,
            'location' => $request->location,
            'credit_card' => $request->credit_card,
            'cash_accept' => $request->cash_accept,
            'bring_card' => $request->bring_card,
            'featured' => $request->featured,
            'speed' => $request->speed,
            'accuracy' => $request->accuracy,
            'min_order_amount' => $request->min_order_amount,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' => $request->vendor_id,
            'by_user_id' => $currentUser->id,
            'by_user_type' => $currentUser->type]
        );
        $user->assignRole('store');

        //Redirect to the users.index view and display message
        alert()->success('Store successfully added.', 'Added');
        return redirect()->route('frond.stores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('stores');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {

        $vendors = Vendor::all()->where('active', 1)->sortBy('name');
        $businessType = BusinessType::all()->sortBy('name');
        $imageSize = config('globalconstants.imageSize')['store'];
        return view('frond.stores.edit', compact('store', 'vendors', 'businessType', 'imageSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $currentUser = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:stores,email,' . $store->id,
        ]);

        $imgPath = "";
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['store'];
            $request->image = $this->singleImage($request->file('images'), $imageSize['path'], 'store');
            if (!empty($request->image)) {
                $path = config('globalconstants.imageSize.store')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $vendor->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $store->image);
                }
            }
        }

        //  $request->merge(['type' => 'admin', 'admin_id' => Auth::guard('admin')->user()->id]);

        $request->credit_card = ($request->credit_card) ? 1 : 0;
        $request->featured = ($request->featured) ? 1 : 0;
        $request->bring_card = ($request->bring_card) ? 1 : 0;
        $request->cash_accept = ($request->cash_accept) ? 1 : 0;
        $request->accuracy = ($request->accuracy) ? 1 : 0;
        $request->speed = ($request->speed) ? 1 : 0;

        $store->update([
            'type_id' => $request->type_id,
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'active' => $request->active,
            'image' => $request->image,
            'mobile' => $request->mobile,
            'description' => $request->description,
            'store_timing' => $request->store_timing,
            'location' => $request->location,
            'credit_card' => $request->credit_card,
            'cash_accept' => $request->cash_accept,
            'bring_card' => $request->bring_card,
            'featured' => $request->featured,
            'speed' => $request->speed,
            'accuracy' => $request->accuracy,
            'min_order_amount' => $request->min_order_amount,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' => $request->vendor_id,
            'by_user_id' => $currentUser->id,
            'by_user_type' => $currentUser->type,
        ]);
        //Retreive the name, email and password fields
        alert()->success('Store details successfully updated.', 'Updated');
        return redirect()->route('frond.stores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Store $store)
    {
        $store->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function loadSubcat($id, $sub)
    {
        $cat = BusinessTypeCategory::where('business_type_id', $id)->where('parent_id', 0)->get();
        return view('frond.stores.subcat', compact('cat', 'sub'));
    }

}
