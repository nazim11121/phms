<?php

namespace App\Http\Controllers\Admin\Account;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceSku;
use App\Models\Expense;
use App\Models\Staff;
use App\Models\StaffSalary;
use App\Models\Category;
use App\Models\User;
use App\Models\Customer;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

class AccountController extends Controller
{
    protected $services;
    use InvoiceDataHandler;

    /**
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

    public function credited(){

        $orderDate = DB::table('invoices')
        ->select(DB::raw('DATE(created_at) as date'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'desc')
        ->get();

        $orderData = DB::table('invoices')
        ->select('grand_total',DB::raw('DATE(created_at) as date2'))
        ->where('payment_status','Paid')
        ->orderBy('date2', 'desc')
        ->get();

        $tableTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $parcelTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $deliveryTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $newTotal = Invoice::where('payment_status','Paid')->sum('grand_total');

        $creditedTotal = Invoice::where('payment_status','Paid')->sum('grand_total');

        return view('admin.account.credit',compact('orderDate','orderData','tableTotal','parcelTotal','deliveryTotal','newTotal','creditedTotal'));
    }

    public function creditedData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $credited = Invoice::with('sku')->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->get();

        $creditedTotal = Invoice::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->sum('grand_total');

        return response()->json([ 'credited'=>$credited,'creditedTotal'=>$creditedTotal]);
    }

    public function debited(){

        $expenseDate = DB::table('expenses')
        ->select('comments','amount',DB::raw('DATE(created_at) as date'))
        // ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'desc')
        ->get();

        $expenseData = DB::table('expenses')
        ->select('comments','amount',DB::raw('DATE(created_at) as date2'))
        ->orderBy('date2', 'desc')
        ->get();

        $staffExpenseTotal = "";
        $otherExpenseTotal = Expense::sum('amount');

        $deditedTotal = Expense::sum('amount');

        return view('admin.account.debit',compact('expenseDate','expenseData','staffExpenseTotal','otherExpenseTotal','deditedTotal'));
    }

    public function deditedData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $dedited = StaffSalary::with('staff')->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->get();

        $deditedTotal = StaffSalary::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)->sum('payable');

        return response()->json([ 'dedited'=>$dedited,'deditedTotal'=>$deditedTotal]);
    }

    public function balance(Request $request){

        $orderDate = DB::table('invoices')
        ->select(DB::raw('DATE(created_at) as date'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'desc')
        ->get();

        $orderData = DB::table('invoices')
        ->select('grand_total',DB::raw('DATE(created_at) as date2'))
        ->where('payment_status','Paid')
        ->orderBy('date2', 'desc')
        ->get();

        $tableTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $parcelTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $deliveryTotal = Invoice::where('payment_status','Paid')->sum('grand_total');
        $newTotal = Invoice::where('payment_status','Paid')->sum('grand_total');

        $creditedTotal = Invoice::where('payment_status','Paid')->sum('grand_total');


        $expenseDate = DB::table('expenses')
        ->select(DB::raw('DATE(created_at) as date'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'desc')
        ->get();

        $expenseData = DB::table('expenses')
        ->select('comments','amount',DB::raw('DATE(created_at) as date2'))
        ->orderBy('date2', 'desc')
        ->get();

        $kitchenExpenseTotal = Expense::where('comments','Kitchen')->sum('amount');
        $staffExpenseTotal = Expense::where('comments','Staff Salary')->sum('amount');
        $otherExpenseTotal = Expense::where('comments','!=','Staff Salary')->sum('amount');

        $deditedTotal = Expense::sum('amount');

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if($request->start_date && $request->end_date){
            $orderDate = DB::table('invoices')
            ->select(DB::raw('DATE(created_at) as date'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

            $orderData = DB::table('invoices')
            ->select('grand_total',DB::raw('DATE(created_at) as date2'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('payment_status','Paid')
            ->orderBy('date2', 'desc')
            ->get();

            $tableTotal = Invoice::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->where('payment_status','Paid')->sum('grand_total');
            $parcelTotal = Invoice::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->where('payment_status','Paid')->sum('grand_total');
            $deliveryTotal = Invoice::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->where('payment_status','Paid')->sum('grand_total');
            $newTotal = Invoice::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->where('payment_status','Paid')->sum('grand_total');

            $creditedTotal = Invoice::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->where('payment_status','Paid')->sum('grand_total');


            $expenseDate = DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as date'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

            $expenseData = DB::table('expenses')
            ->select('comments','amount',DB::raw('DATE(created_at) as date2'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('date2', 'desc')
            ->get();

            $kitchenExpenseTotal = Expense::where('comments','Kitchen')->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->sum('amount');
            $staffExpenseTotal = Expense::where('comments','Staff Salary')->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->sum('amount');
            $otherExpenseTotal = Expense::where('comments','!=','Staff Salary')->where('comments','!=','Kitchen')->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->sum('amount');

            $deditedTotal = Expense::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->sum('amount');
        }

        return view('admin.account.balance',compact('orderDate','orderData','tableTotal','parcelTotal','deliveryTotal','newTotal','creditedTotal','expenseDate','expenseData','kitchenExpenseTotal','staffExpenseTotal','otherExpenseTotal','deditedTotal'));
    }

    public function printData($id){

        $data = Invoice::with('user','sku','sku.medicine')->find($id); 

        return response()->json(['data' => $data]);
    }

}
