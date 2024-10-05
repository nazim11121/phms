<?php

namespace App\Http\Controllers\Admin\Report;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderSku;
use App\Models\Category;
use App\Models\User;
use App\Models\Customer;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Auth;
use DataTables;
use File;
use Exception;
use DB;
use App\Http\Traits\InvoiceDataHandler;

class ReportController extends Controller
{
    protected $services;
    use InvoiceDataHandler;

    /*NN*
     * __construct
     *
     * @param  mixed $ServicesServices
     * @return void
     */
    public function __construct()
    {

        $this->middleware(['permission:List Order'])->only(['index']);
        $this->middleware(['permission:Add Order'])->only(['create']);
        $this->middleware(['permission:Edit Order'])->only(['edit']);
        // $this->middleware(['permission:Show Order'])->only(['show']);
        $this->middleware(['permission:Delete Order'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */

    public function dayWise(){

        $settings = SystemSettings::all();
        $shopName = $settings[3]['settings_value']['store_name'];
        $shopMobile = $settings[3]['settings_value']['store_mobile'];
        $shopAddress = $settings[3]['settings_value']['store_address'];

        return view('admin.report.daywise', compact('shopName','shopMobile','shopAddress'));
    }

    public function getReportData(Request $request)
    {
        $startDate = $request->start_date;
        $report = OrderSku::whereDate('order_skus.created_at', '=', $startDate)->get(); 
        
        $orderCategory = DB::table('order_skus')->join('categories','categories.id','=','order_skus.category_id')
        ->select('categories.name',DB::raw('category_id as category'))
        ->whereDate('order_skus.created_at', '=', $startDate)
        ->groupBy(DB::raw('category'))
        ->get();
        
        $t_table = Order::whereDate('created_at', $startDate)->where('order_type','Table Order')->sum('payable_amount');
        $q_table = Order::whereDate('created_at', $startDate)->where('order_type','Table Order')->count();
        $t_new   = Order::whereDate('created_at', $startDate)->where('order_type','New Order')->sum('payable_amount');
        $q_new   = Order::whereDate('created_at', $startDate)->where('order_type','New Order')->count();
        $t_parcel   = Order::whereDate('created_at', $startDate)->where('order_type','Parcel Order')->sum('payable_amount');
        $q_parcel   = Order::whereDate('created_at', $startDate)->where('order_type','Parcel Order')->count();
        $t_delivery   = Order::whereDate('created_at', $startDate)->where('order_type','Online Delivery')->sum('payable_amount');
        $q_delivery   = Order::whereDate('created_at', $startDate)->where('order_type','Online Delivery')->count();
        
        $all_total   = Order::whereDate('created_at', $startDate)->sum('payable_amount');

        $cash = Order::whereDate('created_at', $startDate)->where('payment_method','Cash')->sum('payable_amount');
        $bkash = Order::whereDate('created_at', $startDate)->where('payment_method','Bkash')->sum('payable_amount');
        $rocket = Order::whereDate('created_at', $startDate)->where('payment_method','Rocket')->sum('payable_amount');
        $nagad = Order::whereDate('created_at', $startDate)->where('payment_method','Nagad')->sum('payable_amount');
        $card = Order::whereDate('created_at', $startDate)->where('payment_method','card')->sum('payable_amount');

        $endDate = $startDate;

        $categories = Category::with(['orderSku' => function($query) use($startDate,$endDate) {
            $query->select('item_name','item_price','category_id', DB::raw('SUM(quantity) as total_quantity'))
                  ->whereDate('created_at','=', [$startDate])
                  ->groupBy('category_id','item_name');
        }])->withCount('orderSku')->get();

        // $categories = Category::with(['orderSku' => function($query) use ($startDate, $endDate) {
        //     $query->select('item_name','item_price','category_id', DB::raw('SUM(quantity) as total_quantity'))
        //           ->whereDate('created_at', [$startDate])  // Filter by date
        //           ->groupBy('category_id');
        // }])->withCount(['orderSku' => function($query) use ($startDate, $endDate) {
        //     $query->whereDate('created_at', [$startDate]); // Filter item count by date range
        // }])->get();

        // return $categories;
        
        $data = [
            't_table'=>$t_table,
            'q_table'=>$q_table,
            't_new'=>$t_new,
            'q_new'=>$q_new,
            't_parcel'=>$t_parcel,
            'q_parcel'=>$q_parcel,
            't_delivery'=>$t_delivery,
            'q_delivery'=>$q_delivery,
            'all_total'=>$all_total,
            'cash'=>$cash,
            'bkash'=>$bkash,
            'rocket'=>$rocket,
            'nagad'=>$nagad,
            'card'=>$card,
            'orderCategory'=>$orderCategory,
            'categories'=>$categories,
        ];

        return response()->json($data);
    }

    public function monthWise(){

        $settings = SystemSettings::all();
        $shopName = $settings[3]['settings_value']['store_name'];
        $shopMobile = $settings[3]['settings_value']['store_mobile'];
        $shopAddress = $settings[3]['settings_value']['store_address'];

        return view('admin.report.monthwise', compact('shopName','shopMobile','shopAddress'));
    }

    public function getMonthReportData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $categories = Category::with(['orderSku' => function($query) use($startDate,$endDate) {
            $query->select('item_name','item_price','category_id', DB::raw('SUM(quantity) as total_quantity'))
                  ->whereDate('created_at','>=', [$startDate])
                  ->whereDate('created_at', '<=', [$endDate])
                  ->groupBy('category_id','item_name');
        }])->withCount('orderSku')->get();

        $orderCategory = DB::table('order_skus')->join('categories','categories.id','=','order_skus.category_id')
        ->select('categories.name',DB::raw('category_id as category'))
        ->whereDate('order_skus.created_at', '>=', $startDate)
        ->whereDate('order_skus.created_at', '<=', $endDate)
        ->groupBy(DB::raw('category'))
        ->get();

        $t_table = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Table Order')->sum('payable_amount');
        $q_table = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Table Order')->count();
        $t_new   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','New Order')->sum('payable_amount');
        $q_new   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','New Order')->count();
        $t_parcel   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Parcel Order')->sum('payable_amount');
        $q_parcel   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Parcel Order')->count();
        $t_delivery   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Online Delivery')->sum('payable_amount');
        $q_delivery   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('order_type','Online Delivery')->count();
        
        $all_total   = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->sum('payable_amount');

        $cash = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('payment_method','Cash')->sum('payable_amount');
        $bkash = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('payment_method','Bkash')->sum('payable_amount');
        $rocket = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('payment_method','Rocket')->sum('payable_amount');
        $nagad = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('payment_method','Nagad')->sum('payable_amount');
        $card = Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->where('payment_method','card')->sum('payable_amount');

        
        $data = [
            't_table'=>$t_table,
            'q_table'=>$q_table,
            't_new'=>$t_new,
            'q_new'=>$q_new,
            't_parcel'=>$t_parcel,
            'q_parcel'=>$q_parcel,
            't_delivery'=>$t_delivery,
            'q_delivery'=>$q_delivery,
            'all_total'=>$all_total,
            'orderCategory'=>$orderCategory,
            'cash'=>$cash,
            'bkash'=>$bkash,
            'rocket'=>$rocket,
            'nagad'=>$nagad,
            'card'=>$card,
            'categories'=>$categories,
        ];
        return response()->json($data);
    }

    public function printData($id){

        $data = Order::with('user','orderSkuList','orderSkuList.service','orderSkuList.category')->find($id); 

        return response()->json(['data' => $data]);
    }

}
