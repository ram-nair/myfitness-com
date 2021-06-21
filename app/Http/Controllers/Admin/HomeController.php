<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Order;
use App\Store;
use App\StoreProduct;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use DB;
use Illuminate\Support\Arr;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $result = array();
            $result['total_customer'] =  User::count();
            $result['order_count']=0;
            $result['order_total'] =0;
            $result['out_of_stock_product_tot']=0;
            $result['accepted_canceled_order_data'] =0;
            $result['stores']  = Store::all()->sortBy('name')->pluck('name', 'id')->toArray();
            $result['top_five_store'] =0;
            $result['top_ten_users']=0;
            /*$customer_graph_data = DB::table('users')->where(DB::raw("YEAR(created_at)"), '=', DB::raw("YEAR(CURRENT_TIMESTAMP)"))
                ->select(
                    DB::raw("DATE_FORMAT(`created_at`, '%b') AS 'label', count(*) as y")
                )->groupBy(DB::raw('label'))
                ->orderBy('created_at', 'ASC')
                ->get()->toArray();
           // $result['customer_graph_data'] =  $this->arrayMappingForGraph(json_decode(json_encode($customer_graph_data), true), 'label', ['y' => 0],$acton_type="monthly");
           $result['total_customer'] =  User::count();
          //  $result['out_of_stock_product_tot'] =  StoreProduct::where('stock','<=',0)->count();

          /*  $result['top_five_store'] = Store::select('name','location','mobile')->withCount(['getOrderCount as order_count' => function($query){
                $query->select(DB::raw('count(id)'));
            }])->orderBy('order_count','desc')->skip(0)->take(5)->get();
            $result['top_ten_users'] = User::select('email','first_name','first_name','last_name','phone')->withCount(['orderCount as order_count' => function($query){
                $query->select(DB::raw('count(id)'));
            }])->orderBy('order_count','desc')->skip(0)->take(10)->get();

            $start_date = $request->start_date ? $request->start_date : date('Y-m-d', strtotime("-6 days"));
            $end_date = $request->end_date ? $request->end_date : date('Y-m-d');

            $orders = Order::selectRaw("DATE_FORMAT(`created_at`, '%Y-%m-%e') AS 'label',count(*) as y,sum(total_amount) as total_revenue")
                ->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)
                ->groupBy('label')
                ->orderBy('label')
                ->get()->toArray();
           // $result['order_graph_data'] =  $this->arrayWeeklyMappingForGraph(json_decode(json_encode($orders), true), 'label', ['y' => 0,'total_revenue'=>0],$start_date);
           // $result['order_count'] =  Order::count();
           // $result['order_total'] =  Order::sum('total_amount');

            $accepted_canceled_order = DB::table('orders')->where(DB::raw("YEAR(created_at)"), '=', DB::raw("YEAR(CURRENT_TIMESTAMP)"))
                ->select(
                    DB::raw("DATE_FORMAT(`created_at`, '%b') AS 'label', COUNT(CASE WHEN order_status='cancelled' THEN 1 END) AS rejected_order_count,
                        COUNT(CASE WHEN order_status='delivered' THEN 1 END) AS accepted_order_count")
                )->groupBy(DB::raw('label'))
                ->orderBy('created_at', 'ASC')
                ->get()->toArray();
           // $result['accepted_canceled_order_data'] =  $this->arrayMappingForGraph(json_decode(json_encode($accepted_canceled_order), true), 'label', ['rejected_order_count' => 0,'accepted_order_count'=>0],$acton_type="monthly");
            //$result['accepted_canceled_order_total'] =  $this->arrayMappingForGraph(json_decode(json_encode($accepted_canceled_order), true), 'label', ['rejected_order_count' => 0,'accepted_order_count'=>0],$acton_type="monthly");
            */
            return view('admin.home')->with(['result'=>$result]);
        }catch (\Exception $e){
            alert()->error('Dashboard failed', 'Failed');
            return $this->responseRedirectBack('Error occurred while opening dashboard.', 'error', true, true);
        }

    }
    function arrayMappingForGraph($array, $key, $extraArray = [],$type="monthly")
    {
        try {
            $myArray = [];
            if ($type == "weekly") {

                $label_array = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            } elseif ($type == "monthly") {
                $label_array = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            } elseif ($type == "yearly") {
                $label_array = [];
                if (count($array) > 0) {
                    for ($j = $array[0]['label']; $j <= date('Y'); $j++) {
                        $label_array[] = $j;
                    }
                }
            }

            for ($i = 0; $i < count($label_array); $i++) {
                $myArray[] = array_merge([$key => $label_array[$i]], $extraArray);
            }
            $newArray = [];
            foreach ($array as $value) {
                $newArray[$value[$key]] = $value;
            }

            $returnArray = [];
            array_map(function ($x) use ($newArray, $key, &$returnArray) {
                if (empty($newArray[$x[$key]])) {
                    $returnArray[] = $x;
                } else {
                    $returnArray[] = $newArray[$x[$key]];
                }
            }, $myArray);

            return $returnArray;
        }catch (Exception $e){
            return array();
        }
    }
    function arrayWeeklyMappingForGraph($array, $key, $extraArray = [],$start_date)
    {
        try {
            $myArray = [];
            $label_array = [];
            for($i=0;$i<=6;$i++){
                $date = date('Y-m-d', strtotime($start_date."+$i days"));
                $label_array[] = $date;
            }



            for ($i = 0; $i < count($label_array); $i++) {
                $myArray[] = array_merge([$key => $label_array[$i]], $extraArray);
            }

            $newArray = [];
            foreach ($array as $value) {
                $newArray[$value[$key]] = $value;
            }

            $returnArray = [];
            array_map(function ($x) use ($newArray, $key, &$returnArray) {
                if (empty($newArray[$x[$key]])) {
                    $returnArray[] = $x;
                } else {
                    $returnArray[] = $newArray[$x[$key]];
                }
            }, $myArray);

            return $returnArray;
        }catch (Exception $e){
            return array();
        }
    }
}
