<?php

namespace App\Http\Controllers\Admin\Medicine;

Use Auth;
use League\Csv\Writer;
use App\Models\Type;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Stock;
use App\Models\Suplier;
use App\Models\MedicineAdd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\MedicineDataTable;

class MedicineController extends Controller
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
    public function index(MedicineDataTable $dataTable)
    {
        set_page_meta(__t('medicine'));
        $medicineList = MedicineAdd::get();
        return $dataTable->render('admin.medicine.index', compact('medicineList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('medicine'));

        $groups = Group::where('status','Active')->get();
        $brands = Brand::where('status','Active')->get();
        $types = Type::where('status','Active')->get();
        $supliers = Suplier::where('status','Active')->get();

        return view('admin.medicine.create', compact('groups','brands','types','supliers'));
    }

    /*
     * This function is worked for
     * Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => 'required',
            'group_id'      => 'required',
            'brand_id'      => 'required',
            'type_id'       => 'required',
            'suplier_id'    => 'nullable', 
            'quantity'      => 'required', 
            'buying_price'  => 'nullable', 
            'selling_price' => 'required', 
            'expired_date'  => 'nullable',
            'status'  => 'nullable',
        ]);

        $medicineAdd = new MedicineAdd();
        $medicineAdd->name = $request->name;
        $medicineAdd->group_id = $request->group_id;
        $medicineAdd->brand_id = $request->brand_id;
        $medicineAdd->type_id = $request->type_id;
        $medicineAdd->suplier_id = $request->suplier_id;
        $medicineAdd->available_stock = $request->quantity;
        $medicineAdd->buying_price = $request->buying_price;
        $medicineAdd->selling_price = $request->selling_price;
        $medicineAdd->expired_date = $request->expired_date;
        $medicineAdd->created_by = Auth::id();
        $medicineAdd->save();

        $stock = new Stock();
        $stock->medicine_id = $medicineAdd->id;
        $stock->previous = 0;
        $stock->new = $request->quantity;
        $stock->available_stock = $request->quantity;
        $stock->selling_price = $request->selling_price;
        $stock->expired_date = $stock->expired_date;
        $stock->created_by = Auth::id();
        $stock->save();
       
        if (!empty($medicineAdd)) {
            flash(__t('medicine_create_successful'))->success();
        } else {
            flash(__t('medicine_create_failed'))->error();
        }

        return redirect()->route('admin.medicine.index');
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
        $stocks = Stock::where('medicine_id',$id)->get();
        foreach($stocks as $value){
            $stock = Stock::find($value->id);
            $stock->delete();
        }

        MedicineAdd::find($id)->delete();

        if ($stock) {
            
            flash(__t('medicine_deleted_successfully'))->success();
        } else {
            flash(__t('medicine_delete_failed'))->error();
        }

        return redirect()->route('admin.medicine.index');
    }

    public function stock($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('stock'));

        $medicine = MedicineAdd::with('stock')->find($id);

        return response()->json(['medicine'=>$medicine]);
    }

    public function stockUpdate(Request $request)
    { 
        $medicine = MedicineAdd::find($request->id);
        $medicine->available_stock = $request->available_stock+$request->new;
        $medicine->save();

        $stockId = Stock::where('medicine_id',$request->id)->get()->first(); 
        $stockUpdate = new Stock();
        $stockUpdate->medicine_id = $request->id;
        $stockUpdate->previous = $request->available_stock;
        $stockUpdate->new = $request->new;
        $stockUpdate->available_stock = $request->available_stock+$request->new;
        $stockUpdate->expired_date = $request->expired_date;
        $stockUpdate->buying_price = $request->buying_price;
        $stockUpdate->selling_price = $request->selling_price;
        $stockUpdate->save();

        if ($stockUpdate) {
            
            flash(__t('stock_updated_successfully'))->success();
        } else {
            flash(__t('stock_update_failed'))->error();
        }

        return redirect()->route('admin.medicine.index');
    }

}
