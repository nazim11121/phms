<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\MedicineAdd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\PurchaseDataTable;
use App\Services\UniqueNumberService;
use Illuminate\Support\Facades\Redirect;
use PDF;
use Excel;

class PurchaseController extends Controller
{
    protected $uniqueNumberService;
    /**
     * __construct
    **/
    public function __construct(UniqueNumberService $uniqueNumberService,)
    {
        $this->uniqueNumberService  = $uniqueNumberService;
        $this->middleware(['permission:List User'])->only(['index']);
        $this->middleware(['permission:Add User'])->only(['create']);
        $this->middleware(['permission:Edit User'])->only(['edit']);
        $this->middleware(['permission:Delete User'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index()
    {
        set_page_meta(__('custom.purchase'));

        $perchaseList = Purchase::distinct()->get()->groupBy('purchase_no');

        return view('admin.purchase.index', compact('perchaseList'));
    }

    /**
     * create
     *
     * @return void
     */
    public function create(PurchaseDataTable $dataTable)
    {
        set_page_meta(__('custom.perchage'));

        $medicineList = MedicineAdd::latest()->get();
        return $dataTable->render('admin.purchase.create', compact('medicineList'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        
        $selectedMedicineIds = $request->input('medicines');
        $quantities = $request->input('quantities');

        if (!$selectedMedicineIds) {
            return back()->with('error', 'No medicines selected!');
        }

        $selectedMedicines = MedicineAdd::whereIn('id', $selectedMedicineIds)->get();

        $purchase_id=$this->uniqueNumberService->generatePurchaseUniqueNumber('Pur-');

        foreach ($selectedMedicines as $medicine) {
            $purchase = new Purchase();
            $purchase->purchase_no = $purchase_id;
            $purchase->medicine_id = $medicine->id;
            $purchase->quantity = $quantities[$medicine->id];
            $purchase->save();
        }

        if ($purchase) {
            flash(__('custom.purchase_list_create_successful'))->success();
        } else {
            flash(__('custom.purchase_list_create_failed'))->error();
        }

        return redirect()->route('admin.purchase.index');
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        set_page_meta(__('custom.edit_purchase_list'));

        $medicineList = MedicineAdd::latest()->get();
        $purchase = Purchase::where('purchase_no',$id)->get();
        $purchase_no = $id;
        return view('admin.purchase.edit', compact('medicineList','purchase','purchase_no'));
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

        $selectedMedicineIds = $request->input('medicines');
        $quantities = $request->input('quantities');

        if (!$selectedMedicineIds) {
            return back()->with('error', 'No medicines selected!');
        }

        $selectedMedicines = MedicineAdd::whereIn('id', $selectedMedicineIds)->get();

        $prePurchaseId = Purchase::where('purchase_no', $id)->get();
        foreach ($prePurchaseId as $value) {
            
            $prePurchase = Purchase::find($value->id);
            $prePurchase->delete();
        }

        foreach ($selectedMedicines as $medicine) {

            $purchase = new Purchase();
            $purchase->purchase_no = $id;
            $purchase->medicine_id = $medicine->id;
            $purchase->quantity = $quantities[$medicine->id];
            $purchase->save();
        }


        if ($purchase) {
            flash(__('custom.purchase_list_updated_successful'))->success();
        } else {
            flash(__('custom.purchase_list_updated_failed'))->error();
        }

        return redirect()->route('admin.purchase.index');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $purchageId = Purchase::where('purchase_no',$id)->get();

        foreach($purchageId as $value){

            $purchase = Purchase::find($value->id);
            $purchase->delete();
        }
        
        if ($purchase) {
            flash(__('custom.purchase_list_deleted_successful'))->warning();
        } else {
            flash(__('custom.purchase_list_deleted_failed'))->error();
        }
        return redirect()->back();
    }

    public function print($id){
        
        $purchase = Purchase::with('medicine','medicine.brand')->where('purchase_no', $id)->get();
        $purchase_no = $id;
        $settings = SystemSettings::all();
        $shopName = $settings[3]['settings_value']['store_name'];
        $shopMobile = $settings[3]['settings_value']['store_mobile'];
        $shopAddress = $settings[3]['settings_value']['store_address'];

        return response()->json(['purchase'=>$purchase, 'purchase_no'=>$purchase_no, 'storeName'=>$shopName, 'storeAddress'=>$shopAddress, 'storeMobile'=>$shopMobile]);
    }

}
