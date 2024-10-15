<?php

namespace App\Http\Controllers\Admin\Customer;

use App\DataTables\CustomerDataTable;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Image;
use Storage;
use PDF;
use Excel;

class CustomersController extends Controller
{

    protected $userService;
    protected $roleService;

    /**
     * __construct
     *
     * @param  mixed $userService
     * @param  mixed $roleService
     * @return void
     */
    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService  = $userService;
        $this->roleService  = $roleService;

        $this->middleware(['permission:List Customer'])->only(['index']);
        $this->middleware(['permission:Add Customer'])->only(['create']);
        $this->middleware(['permission:Edit Customer'])->only(['edit']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(CustomerDataTable $dataTable)
    {
        set_page_meta(__('custom.customers'));
        $customerList = Invoice::latest()->get();
        
        return $dataTable->render('admin.customer.index', compact('customerList'));
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {

        $roles = $this->roleService->get();

        set_page_meta(__('custom.add_user'));
        return view('admin.administration.users.create', compact('roles'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        if ($this->userService->createOrUpdate($data)) {
            flash(__('custom.user_create_successful'))->success();
        } else {
            flash(__('custom.user_create_failed'))->error();
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $user = User::with('customer')->find($id);
        $roles = $this->roleService->get();

        set_page_meta(__('custom.edit_customer'));
        return view('admin.customer.edit', compact('user', 'roles'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        // $data = $request->validated();
        
        $data = User::find($id);
        $ImageName = 'default.jpg';
        if ($request->hasFile('avatar')) {
                $file = request()->file('avatar');
                $ImageName = time() . "-" . request('avatar')->getClientOriginalName();
                $ImageName = str_replace(' ', '-', $ImageName);
                Image::make($file)->fit(370, 253, function ($constraint) {
                $constraint->aspectRatio();
            })->encode()->save(storage_path('/app/public/users/') . $ImageName);
            $data->avatar = $ImageName;
        }
        $data->name  = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        if($request->password!=''){
            $data->password = Hash::make($request->password);
        }else{
            $data->password = $data->password;
        }
        $data->save();

        $customerId = Customer::where('user_id', $id)->get()->pluck('id')->first();
        $customer = Customer::find($customerId);
        $customer->delivery_address = $request->delivery_address;
        $customer->save();

        if ($customer) {
            flash(__('custom.customer_updated_successful'))->success();
        } else {
            flash(__('custom.customer_updated_failed'))->error();
        }

        return redirect()->route('admin.customers.index');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $customerId = Customer::where('user_id', $id)->get()->pluck('id')->first();
        $customer = Customer::find($customerId);
        $customer->delete();
        $user->delete();

        if ($user) {
            flash(__('custom.customer_deleted_successful'))->warning();
        } else {
            flash(__('custom.customer_deleted_failed'))->error();
        }
        return redirect()->back();
    }


    /**
     * profile
     *
     * @return void
     */
    public function profile()
    {
        $user = $this->userService->get(Auth::id());

        set_page_meta(__('custom.edit_profile'));
        return view('admin.administration.users.profile', compact('user'));
    }

    /**
     * updateProfile
     *
     * @param  mixed $request
     * @param  mixed $profile
     * @return void
     */
    public function updateProfile(ProfileRequest $request, $profile)
    {
        $data = $request->validated();

        if ($this->userService->updateProfile($data, Auth::id())) {
            flash(__('custom.profile_update_successful'))->success();
        } else {
            flash(__('custom.profile_update_failed'))->error();
        }

        return redirect()->route('admin.dashboard');
    }

}
