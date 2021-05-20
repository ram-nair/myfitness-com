<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\BusinessType;
use App\Http\Controllers\Controller;
use App\ServiceStoreSlot;
use App\Slot;
use App\StoreDaysSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SlotController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Slot::class, 'slot');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = Slot::all();
        return view('admin.slots.index', compact('slots'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $slots = Slot::with(['businessType', 'businessTypeCategory']);
        return Datatables::of($slots)
            ->rawColumns(['actions'])
            ->editColumn('business_type.name', function ($slots) {
                return $slots->businessType->name ?? '';
            })
            ->editColumn('business_type_category.name', function ($slots) {
                return $slots->businessTypeCategory->name ?? '';
            })
            ->editColumn('created_at', function ($slots) {
                return $slots->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($slots) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('store_update')) {
                    $b .= '<a href="' . URL::route('admin.slots.edit', $slots->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('store_delete')) {
                    $storeDaysCount = StoreDaysSlot::where('slot_id', $slots->id)->count();
                    $serviceDaysCount = ServiceStoreSlot::where('slot_id', $slots->id)->count();
                    if ($storeDaysCount >= 1 || $serviceDaysCount >= 1) {
                        $b .= ' <a href="' . URL::route('admin.slots.destroy', $slots->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . URL::route('admin.slots.destroy', $slots->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
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
        $businessType = BusinessType::where('slug', '!=', 'class')->get();
        return view('admin.slots.create', compact('businessType'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
        $start = $request->start_at;
        $end = $request->end_at;
        $validator->after(function ($validator) use ($start, $end) {
            if (empty($start)) {
                $validator->errors()->add("start_at", 'Please select start time');
            }
            if (empty($end)) {
                $validator->errors()->add("end_at", 'Please select end time');
            }
            if (!empty($start) && !empty($end)) {
                $startHour = Carbon::parse($start);
                $endHour = Carbon::parse($end);
                if ($startHour->addMinute()->gt($endHour)) {
                    $validator->errors()->add("end_at", 'End time should be greater than start time.');
                }
            }
        });
        $validator->validate();
        Slot::create([
            'name' => $request->start_at . "-" . $request->end_at,
            'start_at' => $start,
            'end_at' => $end,
            'business_type_id' => $request->business_type_id,
            'business_type_category_id' => $request->business_type_category_id,
            'by_user_id' => auth()->user()->id,
        ]);
        alert()->success('Slot successfully added.', 'Added');
        return redirect()->route('admin.slots.index');
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
    public function edit(Slot $slot)
    {
        $businessType = BusinessType::where('slug', '!=', 'class')->get();
        return view('admin.slots.edit', compact('slot', 'businessType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        $validator = Validator::make(request()->all(), [
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
        $start = $request->start_at;
        $end = $request->end_at;
        $validator->after(function ($validator) use ($start, $end) {
            if (empty($start)) {
                $validator->errors()->add("start_at", 'Please select start time');
            }
            if (empty($end)) {
                $validator->errors()->add("end_at", 'Please select end time');
            }
            if (!empty($start) && !empty($end)) {
                $startHour = Carbon::parse($start);
                $endHour = Carbon::parse($end);
                if ($startHour->addMinute()->gt($endHour)) {
                    $validator->errors()->add("end_at", 'End time should be greater than start time.');
                }
            }
        });
        $validator->validate();
        $slot->name = $request->start_at . "-" . $request->end_at;
        $slot->start_at = $start;
        $slot->end_at = $end;
        $slot->business_type_id = $request->business_type_id;
        $slot->business_type_category_id = $request->business_type_category_id;
        $slot->by_user_id = auth()->user()->id;
        $slot->save();
        alert()->success('Slot details successfully updated.', 'Updated');
        return redirect()->route('admin.slots.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        Slot::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
}
