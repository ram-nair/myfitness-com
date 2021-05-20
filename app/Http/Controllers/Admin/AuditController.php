<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use App\Audit;
use App\ActivityLog;

use Auth;
use URL;
use Yajra\Datatables\Datatables;

class AuditController extends Controller
{
    public function index($type){

    	switch ($type) {
    		case 'brands':
    			$label = 'Brand';
    			break;
    		case 'category':
    			$label = 'Category';
    			break;           
            case 'service-categories':
                $label = 'Service Category';
                break;
    		/*case 'products':
    			$label = 'Product';
    			break;
    			*/
    		case 'business-type':
    			$label = 'Business Type';
    			break;
    		case 'business-type-category':
    			$label = 'Business Type Category';
    			break;
    		default:
    			$label = 'Type';
    			break;
    	}
    	$users = Admin::all();
    	return view('admin.audit.index',compact('users','type','label'));
    }

    public function datatable(Request $request){
        $where = '';
        switch ($request->type) {
    		case 'brands':
    			$auditType = "App\\Brand";
    			$joinTable = 'brands';
    			$fieldName = 'jt.name';
    			break;
      		case 'category':
    			$auditType = "App\\Category";
    			$joinTable = 'categories';
    			$fieldName = 'jt.name';
                $businessTypeCatIds = \App\BusinessTypeCategory::select('id')->where('business_type_id', 1)->get()->implode('id',',');
                if(!empty($businessTypeCatIds)){
                  
                    $where = ' jt.business_type_category_id IN ('.$businessTypeCatIds.')';
                }
                
    			break;
            case 'service-categories':
                $auditType = "App\\Category";
                $joinTable = 'categories';
                $fieldName = 'jt.name';
                $businessTypeCatIds = \App\BusinessTypeCategory::select('id')->where('business_type_id', 1)->get()->implode('id',',');
                if(!empty($businessTypeCatIds)){
                  
                    $where = ' is_service = 1';
                }
                
                break;
    		/*case 'products':
    			$auditType = "App\\Product";
    			$joinTable = 'products';
    			$fieldName = 'jt.name';
    			break;   */ 		
    		case 'business-type':
    			$auditType = "App\\BusinessType";
    			$joinTable = 'business_types';
    			$fieldName = 'jt.name';
    			break;
    		case 'business-type-category':
    			$auditType = "App\\BusinessTypeCategory";
    			$joinTable = 'business_type_categories';
    			$fieldName = 'jt.name';
    			break;
    		default:
    			$auditType = '';
    			break;
    	}
        $audits = Audit::join($joinTable.' as jt','jt.id','audits.auditable_id')
        			->select('audits.event', 'audits.old_values','audits.new_values','audits.created_at', $fieldName.' as name','admins.name as modifiedBy')
                    ->join('admins','admins.id','audits.admin_id')
        			->orderBy('audits.created_at','desc');
        if($auditType != ''){
        	$audits->where('auditable_type',$auditType);
        }
        if ($request->user_id && !empty($request->user_id)) {
            $audits->where('admin_id', $request->user_id);
        }
        if ($request->event_id && !empty($request->event_id)) {
            $audits->where('event', $request->event_id);
        }        
        if ($request->filter_type && !empty($request->filter_type)) {
            $audits->where($fieldName, 'LIKE' ,'%'.$request->filter_type.'%');
        }
        if($where != ''){
            $audits->whereRaw($where);
        }

        
       /* \DB::enableQueryLog();
        $res = $audits->get();
        $query = \DB::getQueryLog();
        dd($res);*/
         return Datatables::of($audits)
            ->editColumn('created_at', function ($audit) {
                return $audit->created_at->format('F d, Y h:ia');
            })
            ->editColumn('old_values', function ($audit) {
                return view('admin.audit.data',['data' => $audit->old_values]);
            })
            ->editColumn('new_values', function ($audit) {
                return view('admin.audit.data',['data' => $audit->new_values]);
            })
            ->make(true);
    }

     public function activityLog($type){

        switch ($type) {
            case 'product':
                $label = 'Product';
                break;
           case 'store':
                $label = 'Store';
                break;
            case 'vendor':
                $label = 'Vendor';
                break;
             case 'service_type_1':
                $label = 'Service Type 1';
                break; 
            case 'service_type_2':
                $label = 'Service Type 2';
                break; 
            case 'service_type_3':
                $label = 'Service Type 3';
                break;  
            case 'store_product':
                $label = 'Store Product';
                break; 
            default:
                $label = 'Type';
                break;
        }
        $users = Admin::all();
        return view('admin.audit.activity_log',compact('users','type','label'));
    }

    public function activityLogDatatable(Request $request){
        $where = '';
        $type = '';
        switch ($request->type) {
            case 'product':
                $joinTable = 'products';
                $fieldName = 'jt.name';
                $type = 'product';
                break;
            case 'service_type_1':
                $joinTable = 'service_products';
                $fieldName = 'jt.name';
                $where = "service_type = 1";
                $type = 'service_products';
                break; 
            case 'service_type_2':
                $joinTable = 'service_products';
                $fieldName = 'jt.name';
                 $where = "service_type = 2";
                 $type = 'service_products';
                break; 
            case 'service_type_3':
                $joinTable = 'service_products';
                $fieldName = 'jt.name';
                 $where = "service_type = 3";
                 $type = 'service_products';
                break;           
           case 'store':
                $joinTable = 'stores';
                $fieldName = 'jt.store_fullname';
                $type = 'store';
                break;
            case 'vendor':
                $joinTable = 'vendors';
                $fieldName = 'jt.name';
                $type = 'vendor';
                break; 
            case 'store_product':
                $joinTable = 'product_stores';
                $fieldName = 'products.name';
                $type = 'store_product';
                break; 
            default:
                $joinTable = '';
                $fieldName = '';
                break;
        }
        $log = ActivityLog::
                join($joinTable.' as jt','jt.id','activity_log.subject_id')
                    ->select('activity_log.description', 'activity_log.properties','activity_log.created_at', $fieldName.' as name','admins.name as modifiedBy')
                    ->join('admins','admins.id','activity_log.causer_id')
                    
                    ->whereRaw('(JSON_EXTRACT(`properties`, "$.old[0]") is not null OR JSON_EXTRACT(`properties`, "$.attributes[0]") is not null)')
                    ->orderBy('activity_log.created_at','desc');
        if(($request->type) == 'store_product'){
            $log->join('products','products.id','jt.product_id');
        }
        if($where != ''){
            $log->whereRaw($where);

        }
        if(!empty($type)){
            $log->where('activity_log.log_name',$type);
        }
        if ($request->user_id && !empty($request->user_id)) {
            $log->where('activity_log.causer_id', $request->user_id);
        }
        if ($request->event_id && !empty($request->event_id)) {
            $log->where('activity_log.description', $request->event_id);
        }        
        if ($request->filter_type && !empty($request->filter_type)) {
            $log->where($fieldName, 'LIKE' ,'%'.$request->filter_type.'%');
        }
        /* \DB::enableQueryLog();
        $res = $log->get();
        $query = \DB::getQueryLog();
        dd($query);*/
         return Datatables::of($log)
            ->editColumn('created_at', function ($log) {
                return $log->created_at->format('F d, Y h:ia');
            })
            ->editColumn('old_values', function ($log) {
                return view('admin.audit.data',['data' => (isset($log['properties']['old'])) ?  $log['properties']['old'] : [] ]);
            })
            ->editColumn('new_values', function ($log) {
                return view('admin.audit.data',['data' => (isset($log['properties']['attributes'])) ? $log['properties']['attributes'] : []]);
            })
            ->make(true);
    }
}
