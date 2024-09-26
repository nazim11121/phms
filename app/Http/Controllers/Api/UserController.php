<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\OrderSkuList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\SmsHandel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Image;

class UserController extends Controller
{
    use SmsHandel;
    public function loginSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials  = array('email' => $request->email, 'password' => $request->password);
        if (auth()->attempt($credentials, $request->has('remember'))) {
            
            return  redirect()->route('admin.dashboard');

        }
        elseif (auth()->guard('customer')->attempt($credentials, $request->has('remember'))) {
            // Customer user
            if(auth()->guard('customer')->user()->is_verified == Customer::STATUS_VERIFIED && auth()->guard('customer')->user()->status == Customer::STATUS_ACTIVE) {
                return  redirect()->route('customer.dashboard');
            }else{
                flash(__('custom.your_account_is_not_verified'))->error();
                Auth::guard('customer')->logout();
                return redirect()->route('customer.auth.login');
            }
        }
        return redirect('/admin/login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Incorrect email address or password',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/admin/login');
    }

    public function mobileOtp(Request $request){

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:11|max:11|unique:users,phone,$id',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{

            $user = New User();
            $user->phone = $request->mobile;
            $user->otp = rand(100000, 999999);
            $user->status = 'inactive';
            $user->save();

            $number = $request->mobile;
            $text = 'Your otp is ' .$user->otp;
       
            $this->sendsms($number,$text);

            return response()->json(['Otp send success', 'user'=>$user], 200);
        }
    }

    public function sendsms($number,$text){       
    
        $url = "http://api.greenweb.com.bd/api.php";
        $API_TOKEN = "9928112209169380492997c862d2016db5fd4655b45c51f83ea9";

        $data= array(
        'to'=>"$number",
        'message'=>"$text",
        'token'=>"$API_TOKEN"
        ); 

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);

        //Error Display
        echo curl_error($ch);

        return $smsresult;
    }

    public function otpVerify(Request $request){
        $otp = User::where('phone', $request->mobile)->where('otp', $request->otp)->get()->pluck('id')->first();
        if($otp){
            return response()->json(['Otp matched success', 'id'=>$otp], 200);
        }else{
            return response()->json(['Failed! Otp not matched']);
        }
    }

    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'mobile' => 'required|min:11|max:11',
            'password' => "required|min:8",
            'confirm_password' => "required|min:8",
            'delivery_address' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
            // return Redirect::back()->withErrors($validator)->withInput();
        }else{

            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->phone = $request->mobile;
            $user->password = Hash::make($request->password);
            $user->status = 'active';
            $user->user_type = 'Customer';
            $user->save();

            $user->assignRole(7);

            $customer = New Customer();
            $customer->user_id =  $user->id;
            $customer->delivery_address = $request->delivery_address;
            $customer->save();

            return response()->json(['Registration success', 'user'=>$user], 200);
        }
    
    }

    public function informationUpdate(Request $request){

        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|min:11|max:11',
            'pickup_address' => 'nullable',
            'delivery_address' => 'nullable',
        ]);
        
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->phone = $request->mobile;
            $user->save();

            $customerId = Customer::where('user_id', $id)->get()->pluck('id')->first();
            $customer   = Customer::find($customerId);
            $customer->pickup_address   = $request->pickup_address;
            $customer->delivery_address = $request->delivery_address;
            $customer->save();

            return response()->json(['Information updated success', 'user'=>$user], 200);
        }
    }

    public function passwordChange(Request $request){

        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();

        $validator = Validator::make($request->all(), [

            'old_password' => "required|min:8",
            'password' => "required|min:8",
            'confirm_password' => "required|min:8",
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            $user = User::findOrFail($id);
            if (Hash::check($request->password, $user->password)) {           
                $user->password = Hash::make($request->password);
                $user->save();

                return response()->json(['Password updated success', 'user'=>$user], 200);
            }else{
                return response()->json(['Password not matched! Please try again.']);
            }
            
        }   
    }

    public function profilePhoto(Request $request){

        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();

        $validator = Validator::make($request->all(), [

            'image' => "required",
        ]);

        if($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);

        }else{

            $user = User::findOrFail($id);

            $ImageName = 'default.jpg';
            if ($request->hasFile('image')) {
                $file = request()->file('image');
                $ImageName = time() . "-" . request('image')->getClientOriginalName();
                $ImageName = str_replace(' ', '-', $ImageName);
                Image::make($file)->fit(370, 253, function ($constraint) {
                $constraint->aspectRatio();
                })->encode()->save(storage_path('/app/public/users/') . $ImageName);
                $user->avatar = $ImageName;
            }

            $user->save();

            return response()->json(['Profile Photo updated success', 'user'=>$user], 200);
        } 
    }

    public function login(Request $request){

        $request->validate([
            'phone' => 'required|min:11',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('phone', 'password'))) {
            $user = User::where('id', Auth::user()->id)->get();
            $token = Auth::user()->createToken('MyAppToken')->plainTextToken;

            return response()->json(['message' => 'Login Success','token' => $token, 'user' => $user], 200);

        }else{
            
            $msg='Login Failed! Login Credentials are not match.';
            return response()->json(['message' => $msg], 400);
        }
    }

    public function deliveryAddress(Request $request){
        
        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();

        $validator = Validator::make($request->all(), [

            'delivery_address' => "required",
        ]);

        if($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);

        }else{

            $user = User::with('customer')->findOrFail($id);
                
            $customer = Customer::findOrFail($user->customer->id);
            $customer->delivery_address = $request->delivery_address;
            $customer->save();

            return response()->json(['Delivery address successfully save', 'customer'=>$customer], 200);
        } 
    }

    public function pickupAddress(Request $request){
        
        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();

        $validator = Validator::make($request->all(), [

            'pickup_address' => "required",
        ]);

        if($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);

        }else{

            $user = User::with('customer')->findOrFail($id);
                
            $customer = Customer::findOrFail($user->customer->id);
            $customer->pickup_address = $request->pickup_address;
            $customer->save();

            return response()->json(['Pickup address successfully save', 'customer'=>$customer], 200);
        } 
    }

    public function order(Request $request){

        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    
        $id = auth()->id();

        $validator = Validator::make($request->all(), [

            'category_id' => "required",
            // 'service_id' => "required",
            // 'price' => "required",
            // 'quantity' => "required",
            // 'subtotal' => "required",
            'product_name' => 'required', 
            'rate' => 'required', 
            'quantity' => 'required', 
            'prices' => 'required', 
            'totalQuantity' => 'nullable', 
            'delivery_charge' => "required",
            'subtotal' => "required",
            'grand_total' => "required",
            'pickup_date' => "required",
            'delivery_date' => "required",
            'pickup_address' => "required",
            'delivery_address' => "required",
            'payment_method' => "nullable",
            'percentage_discount' => 'nullable', 
            'discount_amount' => 'nullable', 
            'paid_amount' => 'nullable',
        ]);

        if($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);

        }else{
            
            // $order_count   = Order::get()->count()+1;
            // $order_no      = '1000'.$order_count;
            $orderID = Order::latest()->pluck('order_no')->first()+1;

            if(empty($orderID)){
                $serialNumber = str_pad(1, 5, '0', STR_PAD_LEFT);
            }else{
                $serialNumber = str_pad($orderID, 5, '0', STR_PAD_LEFT);
            }
            $order = new Order();
            $order->order_by = $id;
            $order->order_no = $serialNumber;
            $order->category_id = $request->category_id;
            $order->subtotal = $request->subtotal;
            $order->total = $request->grand_total;
            $order->grand_total = $request->grand_total;
            $order->delivery_charge = $request->delivery_charge;
            if($request->discount!=null){
                $order->discount = $request->discount;
                $order->discount_amount = $request->discount_amount;
                $order->grand_total = $request->grand_total;
            }else{
                $order->discount = 0;
                $order->discount_amount = 0;
                $order->grand_total = $request->grand_total; 
            }
            $order->pickup_date = $request->pickup_date;
            $order->delivery_date = $request->delivery_date;
            $order->pickup_address = $request->pickup_address;
            $order->delivery_address = $request->delivery_address;
            $order->paid_amount = $request->paid_amount;
            $order->payment_method = $request->payment_method;
            $order->order_type = 'Online';
            $order->status = 'Processing';
            $order->created_by = $id;
            $order->save();

            if($order){
                foreach($request->product_name as  $key=>$val){
                
                    $orderSku = new OrderSkuList();
                    $orderSku->order_id =  $order->id;
                    $orderSku->category_id = $request->category_id[$key];
                    $orderSku->service_id = $val;
                    $orderSku->price = $request->rate[$key];
                    $orderSku->quantity = $request->quantity[$key];
                    $orderSku->subtotal = $request->prices[$key];
                    $orderSku->save();
                    
                }

                $paymentHistory = new PaymentHistory();
                $paymentHistory->order_id = $order->id;
                $paymentHistory->order_no = $order->order_no;
                $paymentHistory->paid_amount = $request->paid_amount;
                $paymentHistory->due_amount = $request->grand_total - $request->paid_amount;
                $paymentHistory->payment_method = $request->payment_method;
                $paymentHistory->save();

                $orders = collect(Order::with(['user'])->where('id', $order->id)->get())->map(function ($order) {
                    return $this->getInvoiceData($order);
                });

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('admin.order.orders_invoice', ['orders' => $orders]);        
                $invoice = 'order-id-' . $order->order_no. '.pdf';
                
                $pdfPath = public_path('storage/invoice/'.$invoice);
                $pdf->save($pdfPath);

                return response()->json(['Order successfully placed', 'order'=>$order], 200);
            }
        }
    }
}
