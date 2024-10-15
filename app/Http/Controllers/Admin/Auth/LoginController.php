<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        return view('admin.auth.login');
    }

    /**
     * loginSubmit
     *
     * @param  mixed $request
     * @return void
     */
    public function loginSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials  = array('email' => $request->email, 'password' => $request->password);
        if (auth()->attempt($credentials, $request->has('remember'))) {
          
            if (auth()->user()->roles[0]->name == 'Admin2') {
                $subscriptionCheck = Subscription::latest()->get();
                $currentMonth = date('F').date('y');
                foreach($subscriptionCheck as $array){
                    if($currentMonth==$array["month"]){
                        return redirect()->route('admin.dashboard');
                    }else{
                        flash('Your Subscription Month is Over. Please Pay for Subscription.')->success();
                        return redirect()->back();
                    }
                }
            }else{
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
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

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/admin/login');
    }
}
