<?php

namespace App\Http\Controllers\Admin\Invoice;

Use Auth;
use League\Csv\Writer;
use App\Models\Type;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Stock;
use App\Models\Suplier;
use App\Models\Invoice;
use App\Models\InvoiceSku;
use App\Models\MedicineAdd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\DueDataTable;
use App\DataTables\InvoiceDataTable;

class InvoiceController extends Controller
{

    /*NN*
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:List Customer'])->only(['index']);
        $this->middleware(['permission:Add Customer'])->only(['create']);
        $this->middleware(['permission:Edit Customer'])->only(['edit']);
        $this->middleware(['permission:Show Customer'])->only(['show']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(InvoiceDataTable $dataTable)
    {
        set_page_meta(__t('invoice'));

        $invoiceList = Invoice::get();
        return $dataTable->render('admin.invoice.index', compact('invoiceList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('invoice'));

        $groups = Group::where('status','Active')->get();
        $brands = Brand::where('status','Active')->get();
        $types = Type::where('status','Active')->get();
        $supliers = Suplier::where('status','Active')->get();
        $medicineList = MedicineAdd::with(['stock','group','brand','type','suplier'])->where('status','Active')->get();

        return view('admin.invoice.create', compact('medicineList','groups','brands','types','supliers'));
    }

    public function addToCart(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|exists:medicine_adds,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        return response()->json([
            'success' => true,
            'product' => $request->all(),
            'message' => 'Product added to cart.'
        ]);
    }

    /*
     * This function is worked for
     * Request Save
     * */

    public function store(Request $request)
    {
        
        $request->validate([

            'total'           => 'required', 
            'grand_total'     => 'required', 
            'discount_percentage'    => 'nullable', 
            'discount_amount'        => 'nullable', 
            'delivery_charge' => 'nullable', 
            'customer_name'   => 'nullable',
            'customer_mobile' => 'nullable',
            'payment_method'  => 'nullable',
            'payment_status'  => 'nullable',
        ]);

        $invoice_no = date('ymd').rand('11','999');

        $invoice = new Invoice();
        $invoice->invoice_no = $invoice_no;
        $invoice->total = $request->total;
        $invoice->grand_total = $request->grand_total;
        $invoice->customer_name = $request->customer_name;
        $invoice->customer_mobile = $request->customer_mobile;
        $invoice->discount_percentage = $request->discount_percentage;
        $invoice->discount_amount = $request->discount_amount;
        $invoice->payable_amount = $request->payable_amount;
        $invoice->payment_method = $request->payment_method;
        if($request->grand_total == $request->payable_amount){
            $invoice->payment_status = "Paid"; 
            $invoice->due = 0; 
        }elseif($request->grand_total > $request->payable_amount){
            $invoice->payment_status = "Partial"; 
            $due = $request->grand_total-$request->payable_amount;
            $invoice->due = $due; 
        }elseif($request->grand_total < $request->payable_amount){
            $invoice->payment_status = "Paid"; 
            $due = 0;
            $invoice->due = $due; 
        }else{
            $invoice->payment_status = "Unpaid";
            $invoice->due = $request->grand_total;  
        }
        $invoice->created_by = Auth::id();
        $invoice->save();

        foreach($request->cart as $key=>$val){
            $sku = new InvoiceSku();
            $sku->invoice_id = $invoice->id;
            $sku->product_id = $val['id'];
            $sku->price = $val['price'];
            $sku->quantity = $val['quantity'];
            $sku->subtotal = $val['subtotal'];
            $sku->created_by = Auth::id();
            $sku->save();

            $stockUpdate = MedicineAdd::find($val['id']);
            $stockUpdate->available_stock = $stockUpdate->available_stock - $val['quantity'];
            $stockUpdate->save();

            $stockId = Stock::where('medicine_id',$val['id'])->latest()->pluck('id')->first();
            $stockUpdate2 = Stock::find($stockId);
            $stockUpdate2->available_stock = $stockUpdate2->available_stock - $val['quantity'];
            $stockUpdate2->save();
        }

        $sku = $request->cart;
        $settings = SystemSettings::all();
        $shopName = $settings[3]['settings_value']['store_name'];
        $shopMobile = $settings[3]['settings_value']['store_mobile'];
        $shopAddress = $settings[3]['settings_value']['store_address'];
       
        if (!empty($sku)) {
            flash(__t('invoice_create_successful'))->success();
        } else {
            flash(__t('invoice_create_failed'))->error();
        }

        return response()->json(['invoice'=>$invoice,'sku'=>$sku,'shopName'=>$shopName,'shopMobile'=>$shopMobile,'shopAddress'=>$shopAddress]);
    }

    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $suplierList = Suplier::find($id);
        return view('admin.suplier.view', compact('suplierList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('medicine'));

        $medicine = MedicineAdd::with('stock')->find($id);
        $groups = Group::where('status','Active')->get();
        $brands = Brand::where('status','Active')->get();
        $types = Type::where('status','Active')->get();
        $supliers = Suplier::where('status','Active')->get();

        return response()->json(['medicine'=>$medicine,'groups'=>$groups,'brands'=>$brands,'types'=>$types,'supliers'=>$supliers]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'group_id'    => 'required',
            'brand_id'    => 'required',
            'type_id'     => 'required',
            'suplier_id'  => 'nullable', 
            'quantity'    => 'required', 
            'buying_price'  => 'nullable', 
            'selling_price'  => 'required', 
            'expired_date'  => 'nullable',
            'status'  => 'nullable',
        ]);
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }

        $medicineAdd = MedicineAdd::findorfail($request->id);
        $medicineAdd->name = $request->name;
        $medicineAdd->group_id = $request->group_id;
        $medicineAdd->brand_id = $request->brand_id;
        $medicineAdd->type_id = $request->type_id;
        $medicineAdd->suplier_id = $request->suplier_id;
        $medicineAdd->available_stock = $request->quantity;
        $medicineAdd->buying_price = $request->buying_price;
        $medicineAdd->selling_price = $request->selling_price;
        $medicineAdd->expired_date = $request->expired_date;
        $medicineAdd->status = $status;
        $medicineAdd->updated_by = Auth::id();
        $medicineAdd->save();
        
        $stockId = Stock::where('medicine_id',$medicineAdd->id)->get()->pluck('id')->first();
        $stock = Stock::findorfail($stockId);
        $stock->previous = 0;
        $stock->new = $request->quantity;
        $stock->available_stock = $request->quantity;
        $stock->selling_price = $request->selling_price;
        $stock->expired_date = $stock->expired_date;
        $stock->updated_by = Auth::id();
        $stock->save();
        
        if (!empty($stock)) {
            flash(__t('medicine_update_successful'))->success();
        } else {
            flash(__t('medicine_update_failed'))->error();
        }

        return redirect()->route('admin.medicine.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {           
        $sku = InvoiceSku::where('invoice_id',$id)->get();
        foreach($sku as $value){
            $sku = InvoiceSku::find($value->id);

            $stockUpdate = MedicineAdd::find($sku->product_id);
            $stockUpdate->available_stock = $stockUpdate->available_stock+$sku->quantity;
            $stockUpdate->save();

            $stockId = Stock::where('medicine_id',$sku->product_id)->latest()->pluck('id')->first();
            $stockUpdate2 = Stock::find($stockId);
            $stockUpdate2->available_stock = $stockUpdate2->available_stock+$sku->quantity;
            $stockUpdate2->save();

            $sku->delete();
        }

        Invoice::find($id)->delete();

        if ($sku) {
            
            flash(__t('invoice_deleted_successfully'))->success();
        } else {
            flash(__t('invoice_delete_failed'))->error();
        }

        return redirect()->route('admin.invoice.index');
    }

    public function due(DueDataTable $dataTable)
    {
        set_page_meta(__t('due_list'));

        $dueList = Invoice::where('due','!=','0')->get();

        return $dataTable->render('admin.invoice.dueList',compact('dueList'));
    }

    public function paymentAdd($id)
    { 
        $data = Invoice::find($id);

        return response()->json($data);
    }

    public function paymentStore(Request $request)
    { 
        $data = Invoice::find($request->order_id);
        if($request->payable_amount == $data->due){
            $data->payable_amount = $data->payable_amount + $request->payable_amount;
            $data->payment_method = $request->payment_method;
            $data->payment_status = 'Paid';
            $data->due = 0;
            $data->save();
        }else{
            $data->payable_amount = $data->payable_amount + $request->payable_amount;
            $data->payment_method = $request->payment_method;
            $data->payment_status = 'Partial';
            $data->due = $data->grand_total - $data->payable_amount;
            $data->save();
        }

        if ($data) {
            flash(('Payment Added Successfully'))->success();
        } else {
            flash(('Payment Add Failed'))->error();
        }
        
        return redirect()->route('admin.invoice.due');
    }

}
