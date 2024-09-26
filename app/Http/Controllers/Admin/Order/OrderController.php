<?php

namespace App\Http\Controllers\Admin\Order;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderSku;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\TableName;
use App\Models\Customer;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Auth;
use File;
use Exception;
use App\Http\Traits\InvoiceDataHandler;
use DB;

class OrderController extends Controller
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

        $this->middleware(['permission:List Customer'])->only(['index']);
        $this->middleware(['permission:Add Customer'])->only(['create']);
        $this->middleware(['permission:Edit Customer'])->only(['edit']);
        // $this->middleware(['permission:Show Order'])->only(['show']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index()
    {
        set_page_meta(__t('order'));
        $orderList = Order::with('orderSku')->latest()->get();

        return view('admin.order.index', compact('orderList'));
    }

    /*
     * This function is worked for
     * purchase create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('table_order'));
        
        $tableName = TableName::where('status','1')->get();
        $category = Category::where('status','Active')->get();
        $item = Item::get();
   
        return view('admin.order.create', compact('category','item','tableName'));
    }

    public function store(Request $request)
    {
        
        // $request->validate([
           
        //     'percentage_discount' => 'nullable', 
        //     'discount_amount' => 'nullable', 
        //     'grandTotal' => 'nullable', 
        //     'category_id' => 'nullable',
        // ]);
      
        $order_no = date('ymd').rand('11','999');
        if($request->order_type=='Table Order'){
            foreach($request->items as $key=>$val){
                
                if($val['quantity']!=0){ 
                    $cat = Item::where('name',$val['name'])->get()->pluck('category')->first();
                    $orderSku = new OrderSku();
                    $orderSku->order_no =  $order_no;
                    $orderSku->category_id = $cat;
                    $orderSku->item_name = $val['name'];
                    $orderSku->item_price = $val['price'];
                    $orderSku->quantity = $val['quantity'];
                    $orderSku->subtotal = $val['subtotal'];
                    $orderSku->save();
                }
            }
            // dd($orderSku);
            $order = new Order();
            $order->order_type = $request->order_type;
            $order->order_no = $orderSku->order_no;
            $order->table_name = $request->table_name;
            $order->total = $request->total;
            $order->discount_amount = 0;
            $order->grand_total = $request->total;
            $order->payment_status = 'Unpaid';
            $order->save();

            if (!empty($order)) {
                flash(__t('table_order_create_successful'))->success();
            } else {
                flash(__t('table_order_create_failed'))->error();
            }

            return redirect()->route('admin.dashboard');

        }elseif($request->order_type=='New Order'){
            $sumTotal = 0;
            foreach($request->items as $key=>$val){
                
                if($val['quantity']!=0){ 
                    $cat = Item::where('name',$val['name'])->get()->pluck('category')->first();
                    $orderSku = new OrderSku();
                    $orderSku->order_no =  $order_no;
                    $orderSku->category_id = $cat;
                    $orderSku->item_name = $val['name'];
                    $orderSku->item_price = $val['price'];
                    $orderSku->quantity = $val['quantity'];
                    $orderSku->subtotal = $val['subtotal'];
                    $sumTotal+=$val['subtotal'];
                    $orderSku->save();
                }
            }

            $order = new Order();
            $order->order_type = $request->order_type;
            $order->order_no = $orderSku->order_no;
            if($request->discount_amount){
                $order->total = $sumTotal;
                $order->discount_amount = $request->discount_amount;
                $order->grand_total = $request->total;
            }else{
                $order->total = $request->total;
                $order->discount_amount = 0;
                $order->grand_total = $request->total;
            }
            $order->payable_amount = $request->payable_amount;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'Paid';
            $order->status = '1';
            $order->customer_name = $request->customer_name;
            $order->customer_mobile = $request->customer_mobile;
            $order->save();

            if (!empty($order)) {
                flash(__t('new_order_create_successful'))->success();
            } else {
                flash(__t('new_order_create_failed'))->error();
            }

            return redirect()->route('admin.order.index');

        }elseif($request->order_type=='Parcel Order'){
            $sumTotal = 0;
            foreach($request->items as $key=>$val){
                
                if($val['quantity']!=0){ 
                    $cat = Item::where('name',$val['name'])->get()->pluck('category')->first();
                    $orderSku = new OrderSku();
                    $orderSku->order_no =  $order_no;
                    $orderSku->category_id = $cat;
                    $orderSku->item_name = $val['name'];
                    $orderSku->item_price = $val['price'];
                    $orderSku->quantity = $val['quantity'];
                    $orderSku->subtotal = $val['subtotal'];
                    $orderSku->save();
                    $sumTotal+=$val['subtotal'];
                }
            }

            $order = new Order();
            $order->order_type = $request->order_type;
            $order->order_no = $orderSku->order_no;
            $order->total = $request->total;
            $order->discount_amount = 0;
            $order->grand_total = $request->total;
            $order->payment_status = 'Unpaid';
            $order->status = '0';
            $order->customer_name = $request->customer_name;
            $order->customer_mobile = $request->customer_mobile;
            $order->save();

            if (!empty($order)) {
                flash(__t('parcel_order_create_successful'))->success();
            } else {
                flash(__t('parcel_order_create_failed'))->error();
            }

            return redirect()->route('admin.dashboard');

        }else{
            $sumTotal = 0;
            foreach($request->items as $key=>$val){
                
                if($val['quantity']!=0){ 
                    $cat = Item::where('name',$val['name'])->get()->pluck('category')->first();
                    $orderSku = new OrderSku();
                    $orderSku->order_no =  $order_no;
                    $orderSku->category_id = $cat;
                    $orderSku->item_name = $val['name'];
                    $orderSku->item_price = $val['price'];
                    $orderSku->quantity = $val['quantity'];
                    $orderSku->subtotal = $val['subtotal'];
                    $sumTotal+=$val['subtotal'];
                    $orderSku->save();
                }
                
            }

            $order = new Order();
            $order->order_type = $request->order_type;
            $order->order_no = $orderSku->order_no;
            if($request->discount_amount){
                $order->total = $sumTotal;
                $order->discount_amount = $request->discount_amount;
                $order->grand_total = $request->total;
            }else{
                $order->total = $request->total;
                $order->discount_amount = 0;
                $order->grand_total = $request->total;
            }
            $order->delivery_charge = $request->delivery_charge;
            $order->payment_status = 'Unpaid';
            $order->customer_name = $request->customer_name;
            $order->customer_mobile = $request->customer_mobile;
            $order->save();

            if (!empty($order)) {
                flash(__t('online_delivery_order_create_successful'))->success();
            } else {
                flash(__t('online_delivery_order_create_failed'))->error();

            }

            return redirect()->route('admin.dashboard');
        }

    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('order'));

        $order = Order::with('orderSku')->find($id);
        $category = Category::where('status','Active')->get();
        $tableName = TableName::where('status','1')->get();
        $item = Item::get();

        return view('admin.order.edit',compact(['order','category','item','tableName']));
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(Request $request, $id): RedirectResponse
    {
        $preOrder = OrderSku::where('order_no',$request->order_no)->get();
        foreach($preOrder as $key=>$val){
            $preItem = OrderSku::find($val->id);
            if($preItem->kprint!=2){
                $preItem->kprint = 0;
                $preItem->save();
            }
        }

        foreach($request->items as $key=>$val){
                
            if($val['quantity']!=0){ 
                $cat = Item::where('name',$val['name'])->get()->pluck('category')->first();
                $orderSku = new OrderSku();
                $orderSku->order_no =  $request->order_no;
                $orderSku->category_id = $cat;
                $orderSku->item_name = $val['name'];
                $orderSku->item_price = $val['price'];
                $orderSku->quantity = $val['quantity'];
                $orderSku->subtotal = $val['subtotal'];
                $orderSku->kprint = 1;
                $orderSku->save();
            }
        }

        $order = Order::find($request->id);
        $order->total = $request->total;
        $order->grand_total = $request->total;
        $order->save();

        if (!empty($order)) {
                flash(__t('table_order_update_successful'))->success();
        } else {
                flash(__t('table_order_update_failed'))->error();
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {   
        $order = Order::find($id);
        $orderSkuList = OrderSku::where('order_no', $order->order_no)->get();
        foreach($orderSkuList as $val){
            $result = OrderSku::find($val->id);
            $result->delete();
        }
        
        $order->delete();
        
        if ($order) {
            
            flash(__('Order Deleted Successfully'))->success();
        } else {
            flash(__('Order Deleted Failed'))->error();
        }

        return redirect()->route('admin.order.index');
    }

    // New Order
    public function newOrderCreate()
    {
        set_page_meta(__t('add') . ' ' . __t('new_order'));
        $category = Category::where('status','Active')->get();
        $item = Item::get();
        
        return view('admin.order.new', compact('category','item'));
    }

    // Parcel Order
    public function parcelOrderCreate()
    {
        set_page_meta(__t('add') . ' ' . __t('parcel_order'));
        $category = Category::where('status','Active')->get();
        $item = Item::get();
        
        return view('admin.order.parcel', compact('category','item'));
    }

    // Online Delivery Order
    public function deliveryOrderCreate()
    {
        set_page_meta(__t('add') . ' ' . __t('online_delivery_order'));
        $category = Category::where('status','Active')->get();
        $item = Item::get();
        
        return view('admin.order.delivery', compact('category','item'));
    }

    public function printData($id){

        $data = Order::with('orderSku')->find($id); 

        return response()->json(['data' => $data]);
    }

    public function kitchenPrintData($id){

        // $data = DB::table('orders')->join('order_skus','order_skus.order_no','=','orders.order_no')->where('order_skus.kprint','1')->where('orders.id',$id)->get()->first(); 
        $data = Order::with('orderSku2')->find($id);
        return response()->json(['data' => $data]);
    }

    public function getItem($id){

        $item_list = Item::where('category_id', $id)->get();
        return response()->json($item_list);
    }

    public function statusEdit($id)
    {
        $order = Order::find($id);
        return response()->json([ $order]);
    }

    public function getPhoneNumber(Request $request)
    {
        $query = $request->input('mobile_number');
    
        $results = User::with('customer')->where('phone', 'like', "%$query%")->get();
        
        return response()->json(['results' => $results]);
    }

    public function getCustomerDetails(Request $request)
    {
        $query = $request->input('selectedMobile');
    
        $results = User::with('customer')->where('phone', $query)->get();
        
        return response()->json(['results' => $results]);
    }

    public function dueList()
    {
        set_page_meta(__t('due_order_list'));
        $dueOrderList = Order::where('status','Processing')->latest()->get();

        return view('admin.order.orderDueList', compact('dueOrderList'));
    }

    public function paymentAdd($id)
    {
        $orderList = Order::with('user','orderSku')->find($id);
        return response()->json($orderList);
    }

    public function paymentStore(Request $request)
    {
        // return response()->json($request->discount_amount);
        $data = Order::find($request->order_id);
    
            if($request->discount_amount){
                $data->discount_amount = $request->discount_amount;
                $data->payable_amount = $request->paid_amount;
                $data->grand_total = $request->paid_amount;
            }else{
                $data->discount_amount = 0;
                $data->payable_amount = $request->paid_amount;
                $data->grand_total = $request->paid_amount;
            }
            $data->payment_method = $request->payment_method;
            $data->customer_name = $request->customer_name;
            $data->customer_mobile = $request->customer_mobile;
            $data->payment_status = 'Paid';
            $data->status = '1';
            $data->save();

        if ($data) {
            flash(('Payment Added Successfully'))->success();
        } else {
            flash(('Payment Add Failed'))->error();
        }

        $data2 = Order::with('orderSku')->find($data->id); 

        return response()->json($data2);

        // return redirect()->route('admin.order.index');
    }

    public function paymentHistory($id){

        $paymentHistory = Order::with('user','paymentHistory')->where('id', $id)->get();
        return response()->json($paymentHistory);
    }

    public function itemCancel(Request $request){

        $item = OrderSku::find($request->itemId);
        $item->kprint = 2;
        $item->save();

        $order = Order::find($request->orderId);
        $order->total = $order->total-$request->itemPrice;
        $order->grand_total = $order->grand_total-$request->itemPrice;
        $order->save();

        if ($order) {
            flash(('Order Item Cancel Successfully'))->success();
        } else {
            flash(('Order Item Cancel Failed'))->error();
        }
        return response()->json($order);
    }

}
