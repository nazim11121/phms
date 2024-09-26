<?php

namespace App\Http\Controllers\Admin\Staff;

use Auth;
use App\Models\Staff;
use League\Csv\Writer;
use App\Models\Expense;
use App\Models\StaffSalary;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\StaffDataTable;
use App\DataTables\StaffSalaryDataTable;

class StaffController extends Controller
{
    /*N*
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
        $this->middleware(['permission:Show Customer'])->only(['show']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
        $this->middleware(['permission:List Customer'])->only(['staffSalaryList']);
        $this->middleware(['permission:Add Customer'])->only(['staffSalaryCreate']);
        $this->middleware(['permission:Edit Customer'])->only(['staffSalaryEdit']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(StaffDataTable $dataTable)
    {
        set_page_meta(__t('staff'));
        $staffList = Staff::latest()->get();

        return $dataTable->render('admin.staff.index', compact('staffList'));
    }

    /*
     * This function is worked for
     * purchase create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('staff'));

        return view('admin.staff.create');
    }

    /*
     * This function is worked for
     * Product Purchase Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required', 
            'father_name'  => 'required', 
            'mobile'  => 'required', 
            'nid_no'  => 'nullable', 
            'address'  => 'required', 
            'role'  => 'required', 
            'salary'  => 'required', 
            'joinning_date'  => 'required', 
            // 'image' => 'nullable|file|mimes:jpg,jpeg,png', 
        ]);


        $staff = new Staff();
        $staff->name = $request->name;
        $staff->father_name = $request->father_name;
        $staff->mobile = $request->mobile;
        $staff->address = $request->address;
        $staff->nid_no = $request->nid_no;
        $staff->role = $request->role;
        $staff->salary = $request->salary;
        $staff->joinning_date = $request->joinning_date;
        // $merchant->image = $fileName;
        $staff->created_by = Auth::id();
        $staff->save();
       
        if (!empty($staff)) {
            flash(__t('staff_create_successful'))->success();
        } else {
            flash(__t('staff_create_failed'))->error();
        }

        return redirect()->route('admin.staff.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $merchantList = Merchant::find($id);
        // dd($recordList->countData);
        return view('admin.merchant.view', compact('merchantList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('staff'));
        $staff = Staff::find($id);

        return view( 'admin.staff.edit',compact('staff'));
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(Request $request, $id): RedirectResponse
    {
        
        $request->validate([
            'name'  => 'required', 
            'father_name'  => 'required', 
            'mobile'  => 'required', 
            'nid_no'  => 'nullable', 
            'address'  => 'required', 
            'role'  => 'required', 
            'salary'  => 'required', 
            'joinning_date'  => 'required', 
            // 'image' => 'nullable|file|mimes:jpg,jpeg,png', 
        ]);

        $file = $request->file('image');
        if(!empty($file)){
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/staff', $fileName);
        }else{
            $fileName='';
        }

        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        
        $staff = Staff::findorfail($id);
        $staff->name = $request->name;
        $staff->father_name = $request->father_name;
        $staff->mobile = $request->mobile;
        $staff->nid_no = $request->nid_no;
        $staff->address = $request->address;
        $staff->role = $request->role;
        $staff->salary = $request->salary;
        $staff->joinning_date = $request->joinning_date;
        // $merchant->image = $fileName;
        $staff->status = $status;
        $staff->updated_by = Auth::id();
        $staff->save();
        
        if (!empty($staff)) {
            flash(__t('staff_update_successful'))->success();
        } else {
            flash(__t('staff_update_failed'))->error();
        }

        return redirect()->route('admin.staff.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Staff::find($id)->delete()) {
            
            flash(__('custom.staff_deleted_successfully'))->success();
        } else {
            flash(__('custom.staff_delete_failed'))->error();
        }

        return redirect()->route('admin.staff.index');
    }

    // Staff Salary
    public function staffSalaryList(StaffSalaryDataTable $dataTable){

        set_page_meta(__t('staffSalary'));
        $staffSalaryList = StaffSalary::latest()->get();

        return $dataTable->render('admin.staff.salary.index', compact('staffSalaryList'));
    }

    public function staffSalaryCreate(){

        $staffList = Staff::where('status','Active')->get();
        return view('admin.staff.salary.create', compact('staffList'));
    }

    public function getStaffSalary($id){

        $staffSalary = Staff::where('status','Active')->get()->pluck('salary')->first();
        return response()->json($staffSalary);
    }

    public function staffSalaryStore(Request $request): RedirectResponse
    {
        $request->validate([
            'staff_id'  => 'required', 
            'salary'  => 'required', 
            'month'  => 'required',  
            'payable'  => 'required',
            'payment_method'  => 'required', 
        ]);

        $staffSalary = new StaffSalary();
        $staffSalary->staff_id = $request->staff_id;
        $staffSalary->salary = $request->salary;
        $staffSalary->month = $request->month;
        $staffSalary->payable = $request->payable;
        $staffSalary->payment_method = $request->payment_method;
        $staffSalary->created_by = Auth::id();
        $staffSalary->save();

        $expense = new Expense();
        $expense->name = $staffSalary->id;
        $expense->comments = 'Staff Salary';
        $expense->amount = $request->payable;
        $expense->created_by = Auth::id();
        $expense->save();
       
        if (!empty($staffSalary)) {
            flash(__t('staff_salary_create_successful'))->success();
        } else {
            flash(__t('staff_salary_create_failed'))->error();
        }

        return redirect()->route('admin.staff.salary.list');
    }

    public function staffSalaryEdit($id){

        $staffSalary = StaffSalary::find($id);
        return view('admin.staff.salary.edit', compact('staffSalary'));
    }

    public function staffSalaryUpdate(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'staff_id'  => 'required', 
            'salary'  => 'required', 
            'month'  => 'required', 
            'payable'  => 'required',
            'payment_method'  => 'required', 
        ]);

        $staffSalary = StaffSalary::find($id);
        $staffSalary->staff_id = $request->staff_id;
        $staffSalary->salary = $request->salary;
        $staffSalary->month = $request->month;
        $staffSalary->payable = $request->payable;
        $staffSalary->payment_method = $request->payment_method;
        $staffSalary->updated_by = Auth::id();
        $staffSalary->save();

        $id2 = Expense::where('name',$id)->get()->first();
        $expense = Expense::find($id2->id);
        $expense->amount = $request->payable;
        $expense->created_by = Auth::id();
        $expense->save();
       
        if (!empty($staffSalary)) {
            flash(__t('staff_salary_update_successful'))->success();
        } else {
            flash(__t('staff_salary_update_failed'))->error();
        }

        return redirect()->route('admin.staff.salary.list');
    }

    public function staffSalaryDestroy($id): RedirectResponse
    {
        $staffSalary = StaffSalary::find($id);
        $id2 = Expense::where('name',$id)->get()->first();
        $expense = Expense::find($id2->id);
        $expense->delete();
        $staffSalary->delete();
        if ($staffSalary) {
            
            flash(__('custom.staff_salary_deleted_successfully'))->success();
        } else {
            flash(__('custom.staff_salary_delete_failed'))->error();
        }

        return redirect()->route('admin.staff.salary.list');
    }

}
