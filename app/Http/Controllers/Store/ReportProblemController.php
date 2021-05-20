<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseController;
use App\ReportProblem;
use App\Store;
use Auth;
use Illuminate\Http\Request;
use URL;
use Yajra\Datatables\Datatables;

class ReportProblemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return view('frond.report-problem.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = Auth::user();

        $slots = ReportProblem::with('user')->where('store_id', $currentUser->id)
            ->limit(999)->orderBy('created_at')->get();
        return Datatables::of($slots)
            ->rawColumns(['actions'])
            ->editColumn('description', function ($slots) {
                return $slots->description ?? $slots->name;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($slots) {
                $currentUser = Auth::user();
                $order_details = "service-orders.show";
                if ($currentUser->business_type_id == 1) {
                    $order_details = "orders.show";
                }
                $b = '';
                $b .= '<a target="_blank" href="' . URL::route('store.' . $order_details, $slots->order_id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-eye"></i></a>';
                // $b .= ' <a href="' . URL::route('store.report-problem.destroy', $slots->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                return $b;
            })->make(true);
    }

    public function destroy($id)
    {
        ReportProblem::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
}
