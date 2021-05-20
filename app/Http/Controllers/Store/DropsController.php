<?php

namespace App\Http\Controllers\Store;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Slot;
use App\ServiceStoreSlot;
use Auth;
use URL;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class DropsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('frond.drops.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = Auth::user();
        $storeSlots = ServiceStoreSlot::with('slots')->where('store_id', $currentUser->id);

        $storeSlots->limit(999)->get()->sortBy('id');
        return Datatables::of($storeSlots)
            ->rawColumns(['actions'])
            ->editColumn('slot', function ($storeSlots) {
                return $storeSlots->slots->slot_name;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($slots) {
                $b = '';
                //$b .= '<a href="' . URL::route('store.drops.edit', $slots->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                $b .= ' <a href="' . URL::route('store.drops.destroy', $slots->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';


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
        $currentUser = Auth::user();
        $slots = Slot::where('business_type_id', $currentUser->business_type_id)->where('business_type_category_id', $currentUser->business_type_category_id)->get();
        return view('frond.drops.create', compact('slots'));
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
        // $this->validate($request, [
        //     'name' => 'required|max:120',
        // ]);

        // dd($request->all());
        $store_id = $currentUser->id;
        $slots = $request->slots;
        foreach ($slots as $slot) {
            $slot = ServiceStoreSlot::create([
                'store_id' => $store_id,
                'slot_id' => $slot
            ]);
        }

        //Redirect to the users.index view and display message
        alert()->success('Slot successfully added.', 'Added');
        return redirect()->route('store.drops.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('slots');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slot = ServiceStoreSlot::with('slots')->find($id);
        return view('frond.drops.edit', compact('slot')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreDaysSlot $slot)
    {
        $this->validate($request, [
            'slot_date' => 'required',
        ]);
        $input['days'] = $request->slot_date;
        $input['capacity'] = $request->capacity;
        $slot->fill($input)->save();
        alert()->success('Slot details successfully updated.', 'Updated');
        return redirect()->route('store.drops.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ServiceStoreSlot::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
}
