<?php

namespace App\Http\Controllers\Store;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Order;
use App\ServiceOrder;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Slot;
use App\StoreDaysSlot;
use Auth;
use URL;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('frond.slots.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = Auth::user();
        $storeSlots = StoreDaysSlot::with('slots')->where('store_id', $currentUser->id);
        if ($request->days) {
            $days = Carbon::createFromFormat('d/m/Y', $request->days)->format('Y-m-d');
            $storeSlots->where('days', $days);
        }
        $storeSlots->get()->sortBy('days');
        return Datatables::of($storeSlots)
            ->rawColumns(['actions'])
            ->editColumn('slot', function ($storeSlots) {
                return $storeSlots->slots->slot_name;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($storeSlots) {
                $b = '';
                $b .= '<a href="' . URL::route('store.slots.edit', $storeSlots->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                $orderSlotCount = Order::where('slot_id', $storeSlots->id)->count();
                $serviceOrderSlotCount = ServiceOrder::where('pick_up_slot_id', $storeSlots->id)->count();
                if ($orderSlotCount >= 1 || $serviceOrderSlotCount >= 1) {
                    $b .= ' <a href="' . URL::route('store.slots.destroy', $storeSlots->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                } else {
                    $b .= ' <a href="' . URL::route('store.slots.destroy', $storeSlots->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $currentUser = Auth::user();
        $slots = Slot::where('business_type_id', $currentUser->business_type_id)->where('business_type_category_id', $currentUser->business_type_category_id)->get();
        $days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];
        //Get all roles and pass it to the view
        return view('frond.slots.create', compact('slots', 'days'));
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
            'slot_date' => 'required_without:daterange',
            'daterange' => 'required_without:slot_date'
        ], [
            'slot_date.required_without' => 'Please Select Date',
            'daterange.required_without' => ' or Date Range'
        ]);

        $currentUser = Auth::user();
        $store_id = $currentUser->id;
        $slots = $request->slots;
        $capacity = $request->capacity;
        $capacity['slot'] = array_filter($capacity['slot']);
        $combined_slots = array_combine($slots['slot'], $capacity['slot']);
        $dates = $request->slot_date;
        $date_range = $request->daterange;
        $exclude = $request->days;
        if ($request->daterange) {
            $range = explode('-', $request->daterange);
            $dates = getAllDates(trim($range[0]), trim($range[1]));
        }

        if (is_array($dates)) {
            foreach ($dates as $dat) {
                $day = Carbon::parse($dat)->format('l');
                if (!empty($exclude)) {
                    if (!in_array($day, $exclude)) {
                        foreach ($combined_slots as $slot => $capacity) {
                            $slot = StoreDaysSlot::create([
                                'store_id' => $store_id,
                                'days' => Carbon::createFromFormat('Y-m-d', $dat)->format('Y-m-d'),
                                'slot_id' => $slot,
                                'capacity' => $capacity
                            ]);
                        }
                    }
                } else {
                    foreach ($combined_slots as $slot => $capacity) {
                        $slot = StoreDaysSlot::create([
                            'store_id' => $store_id,
                            'days' => Carbon::createFromFormat('Y-m-d', $dat)->format('Y-m-d'),
                            'slot_id' => $slot,
                            'capacity' => $capacity
                        ]);
                    }
                }
            }
        } else {
            $day = Carbon::createFromFormat('d/m/Y', $dates)->format('l');
            if (!empty($exclude)) {
                if (!in_array($day, $exclude)) {
                    foreach ($combined_slots as $slot => $capacity) {
                        $slot = StoreDaysSlot::create([
                            'store_id' => $store_id,
                            'days' => Carbon::createFromFormat('d/m/Y', $dates)->format('Y-m-d'),
                            'slot_id' => $slot,
                            'capacity' => $capacity
                        ]);
                    }
                }
            } else {
                foreach ($combined_slots as $slot => $capacity) {
                    $slot = StoreDaysSlot::create([
                        'store_id' => $store_id,
                        'days' => Carbon::createFromFormat('d/m/Y', $dates)->format('Y-m-d'),
                        'slot_id' => $slot,
                        'capacity' => $capacity
                    ]);
                }
            }
        }
        //Redirect to the users.index view and display message
        alert()->success('Slot successfully added.', 'Added');
        return redirect()->route('store.slots.index');
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
        $slot = StoreDaysSlot::with('slots')->find($id);
        return view('frond.slots.edit', compact('slot')); //pass user and roles data to view
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
        return redirect()->route('store.slots.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StoreDaysSlot::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
}
