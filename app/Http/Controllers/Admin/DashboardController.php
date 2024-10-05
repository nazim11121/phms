<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Models\Expense;
use App\Services\Dashboard\DashboardService;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */

    public function __construct(DashboardService $dashboardService)
    {
//        $this->middleware('permission:Show Dashboard');
        $this->services = $dashboardService;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $currentDate = Carbon::now()->toDateString();

        $data['total_order']     = make2digits(Invoice::whereDate('created_at', $currentDate)->count());
        $data['total_amount']    = make2digits(Invoice::where('payment_status','paid')->whereDate('created_at', $currentDate)->sum('payable_amount'));
        $data['total_expense']   = make2digits(Expense::whereDate('created_at', $currentDate)->sum('amount'));
        $data['total_sale']      = make2digits(Invoice::whereDate('created_at', $currentDate)->sum('payable_amount'));
        
        if($data['total_sale']>$data['total_expense']){
            $data['total_profit']  = $data['total_sale'] - $data['total_expense'];
        }else{
           $data['total_profit']  = '00';
        }
        
        
        $data['total_customer']  = make2digits(User::where('user_type','Customer')->count());
        $data['todays_order']    = make2digits(Invoice::whereDate('created_at', $currentDate)->count());
        // $data['todays_income']   = make2digits(Invoice::whereDate('payment_date', $currentDate)->sum('paid_amount'));

        // $data['total_pending_order']    = make2digits(Invoice::where('status', 'Pending')->count());
        // $data['total_delivered_order']  = make2digits(Invoice::where('status', 'Delivered')->count());
        $data['total_order_amount']     = make2digits(Invoice::sum('total'));
       

        $orderList = Invoice::with('sku')->where('payment_status','Unpaid')->whereDate('created_at', $currentDate)->orderBy('id', 'DESC')->paginate(10);
        set_page_meta('Dashboard');
        return view('admin.dashboard.index', compact('orderList','data'));
    }

    /**
     * getTopProduct
     *
     * @param  mixed $request
     * @return void
     */

    public function setLang()
    {
        if (\request()->get('lang')){
            Session::put('lang', \request('lang'));
        }

        return redirect()->back();
    }
}
