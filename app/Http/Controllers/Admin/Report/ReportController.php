<?php

namespace App\Http\Controllers\Admin\Report;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceSku;
use App\Models\Category;
use App\Models\User;
use App\Models\Stock;
use App\Models\Brand;
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
use App\DataTables\StockListDataTable;

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
        
        $t_table = Invoice::whereDate('created_at', $startDate)->sum('payable_amount');
        $q_table = Invoice::whereDate('created_at', $startDate)->count();

        $all_total   = Invoice::whereDate('created_at', $startDate)->sum('payable_amount');

        $cash = Invoice::whereDate('created_at', $startDate)->where('payment_method','Cash')->sum('payable_amount');
        $bkash = Invoice::whereDate('created_at', $startDate)->where('payment_method','Bkash')->sum('payable_amount');
        $rocket = Invoice::whereDate('created_at', $startDate)->where('payment_method','Rocket')->sum('payable_amount');
        $nagad = Invoice::whereDate('created_at', $startDate)->where('payment_method','Nagad')->sum('payable_amount');
        $card = Invoice::whereDate('created_at', $startDate)->where('payment_method','card')->sum('payable_amount');

        $endDate = $startDate;
        $brands = Brand::with(['medicine' => function ($query) use ($startDate, $endDate) {
            // Filter sellings by the selected date range
            $query->withSum(['sku' => function ($query) use ($startDate, $endDate) {
                $query->whereDate('invoice_skus.created_at', $startDate);
            }], 'quantity');
        }])->get();
        
        $data = [
            't_table'=>$t_table,
            'q_table'=>$q_table,
            'all_total'=>$all_total,
            'cash'=>$cash,
            'bkash'=>$bkash,
            'rocket'=>$rocket,
            'nagad'=>$nagad,
            'card'=>$card,
            'brands'=>$brands,
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

        $t_table = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->sum('payable_amount');
        $q_table = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->count();

        $all_total   = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->sum('payable_amount');

        $cash = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->where('payment_method','Cash')->sum('payable_amount');
        $bkash = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->where('payment_method','Bkash')->sum('payable_amount');
        $rocket = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->where('payment_method','Rocket')->sum('payable_amount');
        $nagad = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->where('payment_method','Nagad')->sum('payable_amount');
        $card = Invoice::whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate)->where('payment_method','card')->sum('payable_amount');

        $brands = Brand::with(['medicine' => function ($query) use ($startDate, $endDate) {
            // Filter sellings by the selected date range
            $query->withSum(['sku' => function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', $startDate)->whereDate('created_at', '<=', $endDate);
            }], 'quantity');
        }])->get();
        
        $data = [
            't_table'=>$t_table,
            'q_table'=>$q_table,
            'all_total'=>$all_total,
            'cash'=>$cash,
            'bkash'=>$bkash,
            'rocket'=>$rocket,
            'nagad'=>$nagad,
            'card'=>$card,
            'brands'=>$brands,
        ];

        return response()->json($data);
    }

    public function stockReport(StockListDataTable $dataTable){

        set_page_meta(__t('stock') . ' ' . __t('report'));

        $latestSellings = DB::table('stocks')
            ->select('medicine_id','previous', DB::raw('MAX(id) as latest_id')) // Get the last (latest) entry for each medicine_id
            ->groupBy('medicine_id')
            ->get();

        $stockList = Stock::whereIn('id', $latestSellings->pluck('latest_id'))
            ->get();

        return $dataTable->render('admin.invoice.stockList', compact('stockList'));
    }

    public function printData($id){

        $data = Order::with('user','sku','invoice')->find($id); 

        return response()->json(['data' => $data]);
    }

}
