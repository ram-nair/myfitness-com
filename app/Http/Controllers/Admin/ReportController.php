<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\Http\Controllers\BaseController;
use App\Imports\StoreProductsImport;
use App\Order;
use App\Store;
use App\StoreProduct;
use App\Product;
use App\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use URL;
use DB;
use Yajra\Datatables\Datatables;
use Excel;
use App\Exports\ExportReport;
class ReportController extends BaseController
{

    public function __construct()
    {
//        $this->authorizeResource(Banner::class, 'report');
    }
    // Out Of Stock
    public function out_of_stock(){
        $this->setPageTitle('Out Of Stock Report', 'Out Of Stock Report');
        $stores = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
        $guard_name = Helper::get_guard();
        return view('admin.report.out_of_stock', compact('stores', 'guard_name'));

    }
    public function datatable_out_of_stock(Request $request)
    {
       // $currentUser = $request->user();
        //$isStoreUser = $currentUser->hasRole('store');
        //if ($isStoreUser) {
        //    $products = StoreProduct::with('product','store')->where('store_id', $currentUser->id)->where('stock','<=',0)->select('*');
        //} else {
            $products = Product::where('quantity','<=',0)->select('*');
        //}
        if(!empty($request->start_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $products = $products->whereDate('updated_at','=',$start_date);
        }
        
        return Datatables::of($products)
            ->make(true);
    }
    public function download_out_of_stock_report(Request $request){
        $products = StoreProduct::with('product','store')->where('stock','<=',0);
        if ($request->store_id) {
            $products = $products->where('store_id', $request->store_id);
        }
        $products = $products->get();
        $increment_value = 1;
        $product_list = array();
        foreach ($products as $p){
            $product_data['sl_no'] =  $increment_value;
            $product_data['name'] =  ($p->product)?$p->product->name:"";
            $product_data['sku'] =  ($p->product)?$p->product->sku:"";
            $product_data['store_name'] =  ($p->store)?$p->store->name:"";
            $product_data['unit_price'] =  $p->unit_price;
            $product_list[] = $product_data;
            $increment_value++;
        }
        $header = [
            'SL.NO',
            'Name',
            'Sku',
            'Store Name',
            'Unit Price',
            ];
        return Excel::download(new ExportReport($product_list,$header), 'out_of_stock.xlsx');

    }

    // User Listing
    public function user_listing(){

        $this->setPageTitle('User Listing', 'User Listing');
        $guard_name = Helper::get_guard();
        return view('admin.report.user_listing', compact('guard_name'));

    }
    public function datatable_user_listing(Request $request)
    {
        $users = User::select('*');
        return Datatables::of($users)
            ->addColumn('name', function ($users) {
                return $users->first_name.' '.$users->last_name;
            })->addColumn('community', function ($users) {
               return (count($users->community)>0) ? implode(', ', Arr::pluck($users->community, 'name')):"";
            })->addColumn('total_orders', function ($users) {
                return count($users->orderCount) + count($users->ClassOrderCount) + count($users->ServiceOrderCount);
            })->addColumn('life_time_revenue', function ($users) {
                $order_array = (count($users->orderCount)>0) ? Arr::pluck($users->orderCount, 'total_amount'):[];
                $class_order_array = (count($users->ClassOrderCount)>0) ? Arr::pluck($users->ClassOrderCount, 'total_amount'):[];
                $service_order_array = (count($users->ServiceOrderCount)>0) ? Arr::pluck($users->ServiceOrderCount, 'total_amount'):[];
                return  array_sum($order_array) + array_sum($class_order_array) +array_sum($service_order_array);
            })->addColumn('business_type_order', function ($users) {
                $store_ids = Arr::pluck($users->orderCount, 'store_id');
                return $this->getBusinessType($store_ids);

           
            })
            ->make(true);
    }
    public function getBusinessType($store_ids){
        try{
            $store_data = Store::with('businessType')->whereIn('id',$store_ids)->get();
            $business_type_order = "";
            foreach ($store_data as $store){
                $business_type_order .= $store->businessType->name.', ';
            }
            return implode(',', array_unique(explode(', ',rtrim($business_type_order,', '))));
        }catch (\Exception $e){
            return "";
        }

    }
    public function download_user_listing_report(Request $request){
        $users = User::select('*')->get();

        $increment_value = 1;
        $users_list = array();
        foreach ($users as $user){
            $order_count =  count($user->orderCount) + count($user->ClassOrderCount) + count($user->ServiceOrderCount);
            $order_array = (count($user->orderCount)>0) ? Arr::pluck($user->orderCount, 'total_amount'):[];
            $class_order_array = (count($user->ClassOrderCount)>0) ? Arr::pluck($user->ClassOrderCount, 'total_amount'):[];
            $service_order_array = (count($user->ServiceOrderCount)>0) ? Arr::pluck($user->ServiceOrderCount, 'total_amount'):[];
            $store_ids = Arr::pluck($user->orderCount, 'store_id');


            $user_data['id'] =  $user->id;
            $user_data['name'] =  $user->name;
            $user_data['phone'] =  $user->phone;
            $user_data['email'] =  $user->email;
            $user_data['community'] = (count($user->community)>0) ? implode(', ', Arr::pluck($user->community, 'name')):"";
            $user_data['life_time_revenue'] =  array_sum($order_array) + array_sum($class_order_array) +array_sum($service_order_array);
            $user_data['total_orders'] =  $order_count;
            $user_data['business_type_order'] =  $this->getBusinessType($store_ids);;
            $user_data['revenue_from_classes'] =  array_sum($class_order_array);
            $user_data['revenue_from_services'] =  array_sum($service_order_array);
            $users_list[] = $user_data;
            $increment_value++;
        }
        $header = [
            'Id',
            'Name',
            'Mobile',
            'Email',
            'Community Name',
            'Customer Life Time Revenue',
            'Total Orders',
            'Business Type 1 Order',
            'Revenue from Classes',
            'Revenue From Services',
        ];
        return Excel::download(new ExportReport($users_list,$header), 'user_listing.xlsx');

    }

    // Canceled Order
    public function canceled_order(){
        $this->setPageTitle('Canceled Order', 'Canceled Order');
        $guard_name = Helper::get_guard();
        $stores = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
        return view('admin.report.canceled_order', compact('guard_name','stores'));


    }
    public function datatable_canceled_order(Request $request)
    {
        $order = Order::with('user','store')->where('payment_status',2);
        if ($request->store_id) {
            $order = $order->where('store_id', $request->store_id);
        }
        if ($request->payment_type) {
            $order = $order->where('payment_type', $request->payment_type);
        }
        if ($request->order_status) {
            $order = $order->where('order_status', $request->order_status);
        }
        return Datatables::of($order)
            ->addColumn('name', function ($order) {
                return $order->user->first_name.' '.$order->user->last_name;
            })->editColumn('created_at', function ($order) {
                return date('d-m-Y h:i A',strtotime($order->created_at));
            })->editColumn('payment_type', function ($order) {
                if($order->payment_type == "credit_card"){
                  $payment_type = "Credit Card";
                }elseif($order->payment_type == "card_reader"){
                    $payment_type = "Card Reader";
                }elseif($order->payment_type == "online_pay"){
                    $payment_type = "Online Pay";
                }elseif($order->payment_type == "cash_on_delivery" || $order->payment_type == "cash"){
                    $payment_type = "Cash On Delivery";
                }else{
                    $payment_type = $order->payment_type;
                }
                return $payment_type;
            })->editColumn('payment_status', function ($order) {
                $payment_status = "Cancelled";
                 return $payment_status;
            })->editColumn('order_status', function ($order) {
               if($order->order_status == "submitted"){
                   $order_status = "Submitted";
               }elseif($order->order_status == "assigned"){
                   $order_status = "Assigned";
               }elseif($order->order_status == "out_for_delivery"){
                   $order_status = "Out For Delivery";
               }elseif($order->order_status == "delivered"){
                   $order_status = "Delivered";
               }elseif($order->order_status == "cancelled"){
                   $order_status = "Cancelled";
               }else{
                   $order_status = "";
               }
               return $order_status;
            })->editColumn('store_type', function ($order) {
                return ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            })
            ->make(true);
    }
    public function download_canceled_order_report(Request $request){
         $orders = Order::with('user','store')->where('payment_status',2);
        if ($request->store_id) {
            $orders = $orders->where('store_id', $request->store_id);
        }
        if ($request->payment_type) {
            $orders = $orders->where('payment_type', $request->payment_type);
        }
        if ($request->order_status) {
            $orders = $orders->where('order_status', $request->order_status);
        }
        $orders =  $orders->get();

        $order_list = array();
        foreach ($orders as $order){
            if($order->payment_type == "credit_card"){
                $payment_type = "Credit Card";
            }elseif($order->payment_type == "card_reader"){
                $payment_type = "Card Reader";
            }elseif($order->payment_type == "online_pay"){
                $payment_type = "Online Pay";
            }elseif($order->payment_type == "cash_on_delivery" || $order->payment_type == "cash"){
                $payment_type = "Cash On Delivery";
            }else{
                $payment_type = $order->payment_type;
            }
            $payment_status = "Cancelled";
            if($order->order_status == "submitted"){
                $order_status = "Submitted";
            }elseif($order->order_status == "assigned"){
                $order_status = "Assigned";
            }elseif($order->order_status == "out_for_delivery"){
                $order_status = "Out For Delivery";
            }elseif($order->order_status == "delivered"){
                $order_status = "Delivered";
            }elseif($order->order_status == "cancelled"){
                $order_status = "Cancelled";
            }else{
                $order_status = "";
            }


            $order_data['order_number'] =  $order->id;
            $order_data['name'] =  ($order->user)?$order->user->first_name.' '.$order->user->last_name:"";
            $order_data['phone'] =  ($order->user)?$order->user->phone:"";
            $order_data['email'] =  ($order->user)?$order->user->email:"";
            $order_data['order_id'] = $order->order_id;
            $order_data['total_amount'] = $order->total_amount;
            $order_data['payment_type'] = $payment_type;
            $order_data['payment_status'] = $payment_status;
            $order_data['order_status'] = $order_status;
            $order_data['order_time'] = date('d-m-Y h:i A',strtotime($order->created_at));
            $order_data['store_type'] = ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            $order_data['store_name'] = ($order->store)?$order->store->name:"";

            $order_list[] = $order_data;

        }
        $header = [
            'Order Number',
            'Customer Name',
            'Customer Number',
            'Customer Email',
            'Order Id',
            'Total Amount',
            'Payment Type',
            'Payment Status',
            'Order Status',
            'Order Time',
            'Store Type',
            'Store Name',
        ];
        return Excel::download(new ExportReport($order_list,$header), 'canceled_order.xlsx');


    }

    // Purchase History
    public function purchase_history(){
        $this->setPageTitle('Purchase History', 'Purchase History');
        $guard_name = Helper::get_guard();
        $stores = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
        return view('admin.report.purchase_history', compact('guard_name','stores'));


    }
    public function datatable_purchase_history(Request $request)
    {
        $currentUser = Auth::user();
        $order = Order::with('user','store');
        if ($request->store_id) {
            $order = $order->where('store_id', $request->store_id);
        }
        if ($request->payment_type) {
            $order = $order->where('payment_type', $request->payment_type);
        }
        if ($request->order_status) {
            $order = $order->where('order_status', $request->order_status);
        }
        if ($request->payment_status) {
            $order = $order->where('payment_status', $request->payment_status);
        }
        return Datatables::of($order)
            ->rawColumns(['actions'])
            ->addColumn('name', function ($order) {
                return $order->user->first_name.' '.$order->user->last_name;
            })->editColumn('created_at', function ($order) {
                return date('d-m-Y h:i A',strtotime($order->created_at));
            })->editColumn('payment_type', function ($order) {
                if($order->payment_type == "credit_card"){
                    $payment_type = "Credit Card";
                }elseif($order->payment_type == "card_reader"){
                    $payment_type = "Card Reader";
                }elseif($order->payment_type == "online_pay"){
                    $payment_type = "Online Pay";
                }elseif($order->payment_type == "cash_on_delivery" || $order->payment_type == "cash"){
                    $payment_type = "Cash On Delivery";
                }else{
                    $payment_type = $order->payment_type;
                }
                return $payment_type;
            })->editColumn('payment_status', function ($order) {
                if($order->payment_status == 0){
                    $payment_status = "Pending";
                }elseif($order->payment_status == 1){
                    $payment_status = "Completed";
                }elseif($order->payment_status == 2){
                    $payment_status = "Cancelled";
                }elseif($order->payment_status == 3){
                    $payment_status = "Failed";
                }else{
                    $payment_status = "";
                }
                return $payment_status;
            })->editColumn('order_status', function ($order) {
                if($order->order_status == "submitted"){
                    $order_status = "Submitted";
                }elseif($order->order_status == "assigned"){
                    $order_status = "Assigned";
                }elseif($order->order_status == "out_for_delivery"){
                    $order_status = "Out For Delivery";
                }elseif($order->order_status == "delivered"){
                    $order_status = "Delivered";
                }elseif($order->order_status == "cancelled"){
                    $order_status = "Cancelled";
                }else{
                    $order_status = "";
                }
                return $order_status;
            })->editColumn('store_type', function ($order) {
                return ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            })->editColumn('actions', function ($orders) use ($currentUser) {
                $b = '';
                $b .= '<a href="' . URL::route('admin.get-order-detail', $orders->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $b;
            })
            ->make(true);
    }
    public function download_purchase_history_report(Request $request){
        $orders = Order::with('user','store');
        if ($request->store_id) {
            $orders = $orders->where('store_id', $request->store_id);
        }
        if ($request->payment_type) {
            $orders = $orders->where('payment_type', $request->payment_type);
        }
        if ($request->order_status) {
            $orders = $orders->where('order_status', $request->order_status);
        }
        if ($request->payment_status) {
            $orders = $orders->where('payment_status', $request->payment_status);
        }
        $orders =  $orders->get();

        $order_list = array();
        foreach ($orders as $order){
            if($order->payment_type == "credit_card"){
                $payment_type = "Credit Card";
            }elseif($order->payment_type == "card_reader"){
                $payment_type = "Card Reader";
            }elseif($order->payment_type == "online_pay"){
                $payment_type = "Online Pay";
            }elseif($order->payment_type == "cash_on_delivery" || $order->payment_type == "cash"){
                $payment_type = "Cash On Delivery";
            }else{
                $payment_type = $order->payment_type;
            }
            if($order->payment_status == 0){
                $payment_status = "Pending";
            }elseif($order->payment_status == 1){
                $payment_status = "Completed";
            }elseif($order->payment_status == 2){
                $payment_status = "Cancelled";
            }elseif($order->payment_status == 3){
                $payment_status = "Failed";
            }else{
                $payment_status = "";
            }
            if($order->order_status == "submitted"){
                $order_status = "Submitted";
            }elseif($order->order_status == "assigned"){
                $order_status = "Assigned";
            }elseif($order->order_status == "out_for_delivery"){
                $order_status = "Out For Delivery";
            }elseif($order->order_status == "delivered"){
                $order_status = "Delivered";
            }elseif($order->order_status == "cancelled"){
                $order_status = "Cancelled";
            }else{
                $order_status = "";
            }


            $order_data['order_number'] =  $order->id;
            $order_data['name'] =  ($order->user)?$order->user->first_name.' '.$order->user->last_name:"";
            $order_data['phone'] =  ($order->user)?$order->user->phone:"";
            $order_data['order_id'] = $order->order_id;
            $order_data['total_amount'] = $order->total_amount;
            $order_data['payment_type'] = $payment_type;
            $order_data['payment_status'] = $payment_status;
            $order_data['order_status'] = $order_status;
            $order_data['order_time'] = date('d-m-Y h:i A',strtotime($order->created_at));
            $order_data['store_type'] = ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            $order_data['store_name'] = ($order->store)?$order->store->name:"";
            $order_data['accuracy'] = $order->accuracy;
            $order_data['rating'] = $order->rating;
            $order_list[] = $order_data;

        }
        $header = [
            'Order Number',
            'Customer Name',
            'Customer Number',
            'Order Id',
            'Total Amount',
            'Payment Type',
            'Payment Status',
            'Order Status',
            'Order Time',
            'Store Type',
            'Store Name',
            'Accuracy',
            'Rating',
        ];
        return Excel::download(new ExportReport($order_list,$header), 'purchase_history.xlsx');


    }
    public function getOrderDetail($order_id)
    {
        $order = Order::where('id',$order_id)->first();
        $store = $order->store;
        $user = $order->user;
        $address = $order->address;
        $products = $order->orderItem();
        $stockProducts = $order->orderItem()->get();
        $outOfStockProducts = $order->orderItem()->onlyTrashed()->get();
        // dd($outOfStockProducts);
        $this->setPageTitle('Order Details', 'Order Details');
        return view('admin.report.show_order_details', compact('order', 'store', 'user', 'address', 'stockProducts', 'outOfStockProducts'));
    }


    // Average Price
    public function average_price(){

        $this->setPageTitle('Average Price', 'Average Price');
        $guard_name = Helper::get_guard();
        $stores = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
        return view('admin.report.average_price', compact('guard_name','stores'));


    }
    public function datatable_average_price(Request $request)
    {
        $order = Order::with('store')->selectRaw("SUM(total_amount) AS total_price,store_id,count(user_id) as user_count");
        if ($request->store_id) {
            $order = $order->where('store_id', $request->store_id);
        }
        $order = $order->groupBy('store_id');
        return Datatables::of($order)
            ->addColumn('total_price', function ($order) {
                return round($order->total_price/$order->user_count,2);
            })
             ->addColumn('store_type', function ($order) {
                return ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            })->addColumn('recurring_customer', function ($order) {
                $order_customer_ids = Order::where('store_id',$order->store_id)->pluck('user_id')->toArray();
                $order_customers = array_count_values($order_customer_ids);
                $recurring_count = array();
                foreach($order_customers as $key=>$v){
                    if($v>1){
                        $recurring_count[] = $v;
                    }
                }
                return count($recurring_count);

            })
            ->make(true);
    }
    public function download_average_price_report(Request $request){
        $orders = Order::with('store')->selectRaw("SUM(total_amount) AS total_price,store_id,count(user_id) as user_count");
        if ($request->store_id) {
            $orders = $orders->where('store_id', $request->store_id);
        }
        $orders =  $orders->groupBy('store_id')->get();

        $order_list = array();
        foreach ($orders as $order){
            $order_data['store_name'] = ($order->store)?$order->store->name:"";
            $order_data['store_type'] = ($order->store && $order->store->businessTypeCategory)?$order->store->businessTypeCategory->name:"";
            $order_data['total_price'] =  round($order->total_price/$order->user_count,2);
            $order_customer_ids = Order::where('store_id',$order->store_id)->pluck('user_id')->toArray();
            $order_customers = array_count_values($order_customer_ids);
            $recurring_count = array();
            foreach($order_customers as $key=>$v){
                if($v > 1){
                    $recurring_count[] = $v;
                }
            }
            $order_data['total_customer'] = $order->user_count;
            $order_data['recurring_customer'] = count($recurring_count);
            $order_list[] = $order_data;

        }
        $header = [
            'Store Name',
            'Store Type',
            'Avg order prize',
            'Total Customer',
            'Recurring Customer',
        ];
        return Excel::download(new ExportReport($order_list,$header), 'average_price.xlsx');


    }



    // Customer Growth
    public function customer_growth(){
        $this->setPageTitle('Customer Growth', 'Customer Growth');
        $guard_name = Helper::get_guard();
        $stores = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
        return view('admin.report.customer_growth', compact('guard_name','stores'));

    }
    public function datatable_customer_growth(Request $request)
    {
        $order = Order::selectRaw("COUNT(CASE WHEN payment_status='1' THEN 1 END) AS completed_user_count,DATE_FORMAT(`created_at`, '%Y-%m-%e') AS 'date',store_id,count(user_id) as user_count");
        if(!empty($request->start_date) && !empty($request->end_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $order = $order->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date);
        }
        $order = $order->groupBy('date')->orderBy('date');
        return Datatables::of($order)
            ->make(true);
    }
    public function download_customer_growth_report(Request $request){
        $orders = Order::selectRaw("COUNT(CASE WHEN payment_status='1' THEN 1 END) AS completed_user_count,DATE_FORMAT(`created_at`, '%Y-%m-%e') AS 'date',store_id,count(DISTINCT user_id) as user_count");
        if ($request->store_id) {
            $orders = $orders->where('store_id', $request->store_id);
        }
        $orders = $orders->groupBy('date')->orderBy('date')->get();

        $order_list = array();
        foreach ($orders as $order){
            $order_data['date'] =  $order->date;
            $order_data['user_count'] =  $order->user_count;
            $order_data['completed_user_count'] =  $order->completed_user_count;
            $order_data['store_name'] = ($order->store)?$order->store->name:"";
            $order_list[] = $order_data;

        }
        $header = [
            'Date',
            'No of User',
            'No of User Completed Order',
            'Store Name'
        ];
        return Excel::download(new ExportReport($order_list,$header), 'customer_growth.xlsx');
    }

    // Funnel For Order
    public function funnel_for_order(){
        $this->setPageTitle('Funnel For Order', 'Funnel For Order');
        $guard_name = Helper::get_guard();
        return view('admin.report.funnel_for_order', compact('guard_name'));


    }
    public function datatable_funnel_for_order(Request $request)
    {
        $order = Order::selectRaw("COUNT(CASE WHEN payment_status='1' THEN 1 END) AS completed_order_count,
        COUNT(CASE WHEN payment_status='2' THEN 1 END) AS canceled_order_count,
        COUNT(CASE WHEN order_status='cancelled' THEN 1 END) AS rejected_order_count,
        COUNT(CASE WHEN order_status='delivered' THEN 1 END) AS accepted_order_count,
        store_id,count(*) as total_order,DATE_FORMAT(`created_at`, '%Y-%m-%e') AS 'date'");
        if(!empty($request->start_date) && !empty($request->end_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $order = $order->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date);
        }
        $order = $order->groupBy('date')->orderBy('date');
        return Datatables::of($order)
            ->make(true);
    }
    public function download_funnel_for_order_report(Request $request){
        $orders = Order::with('store')->selectRaw("COUNT(CASE WHEN payment_status='1' THEN 1 END) AS completed_order_count,
        COUNT(CASE WHEN payment_status='2' THEN 1 END) AS canceled_order_count,
        COUNT(CASE WHEN order_status='cancelled' THEN 1 END) AS rejected_order_count,
        COUNT(CASE WHEN order_status='delivered' THEN 1 END) AS accepted_order_count,
        store_id,count(*) as total_order");
        if ($request->store_id) {
            $orders = $orders->where('store_id', $request->store_id);
        }
        $orders = $orders->groupBy('store_id')->get();

        $order_list = array();
        foreach ($orders as $order){
            $order_data['store_name'] = ($order->store)?$order->store->name:"";
            $order_data['order_total'] = $order->total_order;
            $order_data['accepted_order'] = $order->accepted_order_count;
            $order_data['rejected_order_count'] = $order->rejected_order_count;
            $order_data['canceled_order_count'] = $order->canceled_order_count;
            $order_data['completed_order_count'] = $order->completed_order_count;
            $order_list[] = $order_data;

        }
        $header = [
            'Store Name',
            'Total Order',
            'Accepted Order',
            'Rejected Order',
            'Canceled Order',
            'Completed Order',
        ];
        return Excel::download(new ExportReport($order_list,$header), 'funnel_for_order.xlsx');


    }

}
