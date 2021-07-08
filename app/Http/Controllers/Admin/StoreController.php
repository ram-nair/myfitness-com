<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Boundary;
use App\Http\Controllers\Controller;
use App\Http\Resources\DropCollection;
use App\Http\Resources\SlotCollection;
use App\Http\Resources\StoreResourceCollection;
use App\Store;
use App\StoreContactDetails;
use App\StoreSuperVisorContactDetails;
use App\Traits\ImageTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class StoreController extends Controller
{
    use ImageTraits;

    public function __construct()
    {
        $this->authorizeResource(Store::class, 'store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('admin.stores.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $stores = Store::select('*');
        
        return Datatables::of($stores)
            ->rawColumns(['actions', 'name'])
            ->editColumn('name', function ($store) {
                $active = $store->active == 1 ? '' : '<span data-toggle="tooltip" title="This store is disabled"><i class="fa fa-ban" style="color:red"></i></span>';
                return $store->store_fullname . ' ' . $active ?? $store->name . ' ' . $active;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($user) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('store_update')) {
                    $b .= '<a href="' . URL::route('admin.stores.edit', $user->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('store_delete')) {
                    if ($user->storeProducts()->where('product_stores.deleted_at', NULL)->get()->isEmpty() && $user->serviceStoreProducts()->where('service_store_products.deleted_at', NULL)->get()->isEmpty()) {
                        $b .= ' <a href="' . URL::route('admin.stores.destroy', $user->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.stores.destroy', $user->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
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
        //Get all roles and pass it to the view
        
        $imageSize = config('globalconstants.imageSize')['store'];

        return view('admin.stores.create', compact('imageSize'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!empty($request->polygon_data)) {
            // dd($polygons);
        }
        $currentUser = $request->user();
        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:stores',
            'mobile' => 'required',
            'password' => 'required|min:6|confirmed',
            'min_order_amount' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'location' => 'required',
            'service_charge' => 'nullable',
            'payment_charge' => 'nullable|max:100',
            'payment_charge_store_dividend' => 'nullable|max:100',
            'payment_charge_provis_dividend' => 'nullable|max:100',
            'contract_start_date' => 'nullable|required_with:contract_end_date',
            'contract_end_date' => 'nullable|required_with:contract_start_date',
        ]);
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['store'];
            $request->image = $this->singleImage($request->file('images'), $imageSize['path'], 'store');
        }

        $request->credit_card = ($request->credit_card) ? 1 : 0;
        $request->featured = ($request->featured) ? 1 : 0;
        $request->bring_card = ($request->bring_card) ? 1 : 0;
        $request->cash_accept = ($request->cash_accept) ? 1 : 0;
        $request->my_location = $request->my_location ?? 0;
        $request->in_store = $request->in_store ?? 0;
        $active = $request->active ?? 0;
        $request->male = ($request->male) ? 1 : 0;
        $request->female = ($request->female) ? 1 : 0;
        $request->any = ($request->any) ? 1 : 0;
        //additional for service-2
        $service_type = BusinessTypeCategory::find($request->business_type_category_id)->service_type;

        $user = Store::create([
            'name' => $request->name,
            'store_fullname' => $request->store_fullname ?? null,
            'email' => $request->email,
            'password' => $request->password,
            'active' => $active,
            'image' => $request->image,
            'mobile' => $request->mobile,
            'description' => $request->description,
            'store_timing' => $request->start_at . "-" . $request->end_at,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'location' => $request->location,
            'credit_card' => $request->credit_card,
            'cash_accept' => $request->cash_accept,
            'bring_card' => $request->bring_card,
            'featured' => $request->featured,
            'speed' => $request->speed ?? 1,
            'accuracy' => $request->accuracy ?? 1,
            'min_order_amount' => $request->min_order_amount,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' => $request->vendor_id,
            'by_user_id' => $currentUser->id,
            'time_to_deliver' => $request->time_to_deliver,
            'by_user_type' => $currentUser->type,
            'on_my_location_charge' => $request->on_my_location_charge,
            'in_store' => $request->in_store,
            'my_location' => $request->my_location,
            'male' => $request->male,
            'female' => $request->female,
            'any' => $request->any,
            'sap_id' => $request->sap_id,
            'service_charge' => $request->service_charge ?? 0,
            'payment_charge' => $request->payment_charge ?? 0,
            'payment_charge_store_dividend' => $request->payment_charge_store_dividend,
            'payment_charge_provis_dividend' => $request->payment_charge_provis_dividend,
            'contract_start_date' => !empty($request->contract_start_date) ? Carbon::createFromFormat('d/m/Y', $request->contract_start_date)->format('Y-m-d') : null,
            'contract_end_date' => !empty($request->contract_end_date) ? Carbon::createFromFormat('d/m/Y', $request->contract_end_date)->format('Y-m-d') : null,
            'sla' => $request->sla ?? null,

        ]);
        $user->store_fullname = $request->store_fullname;
        $user->save();
        $user->assignRole('store');
        if ($request->store) {
            $storeContacts = $request->store;
            foreach ($storeContacts as $key => $data) {
                if ($key == 'new') {
                    foreach ($data as $item) {
                        $user->storeSupervisorContacts()->save(new StoreContactDetails($item));
                    }
                }
            }
        }
        if ($request->supervisor) {
            $superVisors = $request->supervisor;
            foreach ($superVisors as $key => $data) {
                if ($key == 'new') {
                    foreach ($data as $item) {
                        $user->storeSupervisorContacts()->save(new StoreSuperVisorContactDetails($item));
                    }
                }
            }
        }
        if ($request->manager) {
            $managers = $request->manager;
            foreach ($managers as $key => $data) {
                if ($key == 'new') {
                    foreach ($data as $item) {
                        $user->storeSupervisorContacts()->save(new StoreManagerContactDetails($item));
                    }
                }
            }
        }
        if (!empty($request->polygon_data)) {
            $polyPoints = json_decode($request->polygon_data);
            foreach ($polyPoints as $key => $poly) {
                $firstItem = reset($poly);
                array_push($poly, $firstItem);
                $polyData = implode(",", $poly);
                $pos = new Boundary();
                $pos->positions = \DB::raw("PolygonFromText('POLYGON(($polyData))')");
                $pos->store_id = $user->id;
                $pos->save();
            }
        }
        //Redirect to the users.index view and display message
        alert()->success('Store successfully added.', 'Added');
        return redirect()->route('admin.stores.index');
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
        $imageSize = config('globalconstants.imageSize')['store'];
        return view('admin.stores.edit', compact('store', 'imageSize'));
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
        $currentUser = $request->user();
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:stores,email,' . $store->id,
            'min_order_amount' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'location' => 'required',
            'service_charge' => 'nullable',
            'payment_charge' => 'nullable|max:100',
        ]);

        $imgPath = "";
        if ($request->hasFile('images')) {
            $imageSize = config('globalconstants.imageSize')['store'];
            $imgPath= $this->singleImage($request->file('images'), $imageSize['path'], 'store');
            if (!empty($imgPath)) {
                $path = config('globalconstants.imageSize.store')['path'] . '/';
                if (!env('CDN_ENABLED', false)) {
                    \Storage::delete($path . $store->getAttributes()['image']);
                } else {
                    \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $store->image);
                }
            }
        }

        $request->credit_card = $request->credit_card ?? 0;
        $request->featured = $request->featured ?? 0;
       
        $request->cash_accept = $request->cash_accept ?? 0;
        $request->my_location = $request->my_location ?? 0;
      
        $active = $request->active ?? 0;
        $request->any = $request->any ?? 0;
        $store->update([
          
            'name' => $request->name,
            'store_fullname' => $request->store_fullname,
            'email' => $request->email,
            'active' => $active,
            'mobile' => $request->mobile,
            'description' => $request->description,
           
            'location' => $request->location,
            'credit_card' => $request->credit_card,
            'cash_accept' => $request->cash_accept,
            'image' => $imgPath,
            'featured' => $request->featured,
           
            'min_order_amount' => $request->min_order_amount,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' =>1,
            'by_user_id' => $currentUser->id,
            'by_user_type' => $currentUser->type,
            'service_charge' => $request->service_charge,
            'payment_charge' => $request->payment_charge ]);

        /* if (!empty($request->polygon_data)) {
            $polyPoints = json_decode($request->polygon_data);
            foreach ($polyPoints as $key => $poly) {
                $firstItem = reset($poly);
                array_push($poly, $firstItem);
                $polyData = implode(",", $poly);
                $pos = new Boundary();
                $pos->positions = \DB::raw("PolygonFromText('POLYGON(($polyData))')");
                $pos->store_id = $store->id;
                $pos->save();
            }
        }*/
        alert()->success('Store details successfully updated.', 'Updated');
        return redirect()->route('admin.stores.edit',[$store->id]);
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
        if ($id == 'service_type_1' || $id == 'service_type_2' || $id == 'service_type_3') {
            $cat = BusinessTypeCategory::where('service_type', $id)->get();
        } else {
            $cat = BusinessTypeCategory::where('business_type_id', $id)->get();
        }
        return view('admin.stores.subcat', compact('cat', 'sub'));
    }

    public function getStoreByLocation(Request $request)
    {
        $request->validate([
            "business_type_category_id" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);
        $distance = config('settings.distance_radius');
        $businessTypeCategoryId = $request->business_type_category_id;
        $businessTypeId = BusinessTypeCategory::find($businessTypeCategoryId);
        if ($businessTypeId) {
            $businessTypeId = $businessTypeId->business_type_id;
        }
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        // set @p = GeomFromText('POINT(22.4053386588057 70.86240663480157)');
        // select * FROM TestPoly where ST_Contains(pol, @p);
        // $boundary = Boundary::select('store_id')->whereRaw("ST_Contains(positions, GeomFromText('POINT($latitude $longitude)'))")->groupBy('store_id')->get();
        // dd($boundary);
        $query = Store::selectRaw("stores.id, name,image, location, store_timing, start_at, end_at, credit_card, cash_accept, description, service_type, email, mobile, business_type_id, business_type_category_id
        , bring_card, min_order_amount, latitude, longitude, featured, speed, accuracy, location_type, male, female, any, my_location, in_store, on_my_location_charge, time_to_deliver, store_favourites.store_id as favorite_store")
            ->join('store_boundary', 'stores.id', '=', 'store_boundary.store_id')
            ->leftJoin('store_favourites', 'stores.id', '=', 'store_favourites.store_id')
            ->whereRaw("ST_Contains(positions, GeomFromText('POINT($latitude $longitude)'))")
            ->where('active', 1)
            ->where('business_type_id', $businessTypeId)
            ->where('business_type_category_id', $businessTypeCategoryId);
        if ($request->service_type) {
            $query->where('service_type', $request->service_type);
        }
        $stores = $query->groupBy('stores.id')->paginate($request->limit ?? 20);
        //->having("distance", "<", $distance)
        //->orderBy("distance", 'asc')

        if ($stores->isEmpty()) {
            return successResponse(trans('api.no_store_list'), null);
        } else {
            return new StoreResourceCollection($stores);
        }
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        $store_id = $request->store_id;
        $date = $request->date;
        $today = date('Y-m-d');
        $newDate = Carbon::now()->addDay(3)->format('Y-m-d');
        $slots = StoreDaysSlot::has('slots')->where('store_id', $store_id);
        if (!empty($date)) {
            $slots->whereIn('days', $date);
        } else {
            $slots->whereBetween('days', [$today, $newDate]);
        }
        $slots->limit(50);
        $slots->orderBy('days');
        $slots_array = $slots->get();
        return new SlotCollection($slots_array);
    }

    //dropoff api
    public function getDropOff(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        $store_id = $request->store_id;
        $slots = ServiceStoreSlot::where('store_id', $store_id);
        $slots->limit(50);
        $slots->orderBy('slot_id');
        $slots_array = $slots->get();
        return new DropCollection($slots_array);
    }

    public function addMarker(Request $request, Store $store)
    {

        // return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function deleteMarker(Request $request)
    {
        $request->validate([
            'marker_id' => 'required',
        ]);
        $marker = Boundary::find($request->marker_id);
        if ($marker) {
            $marker->delete();
        }
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
}
