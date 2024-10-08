<?php

namespace App\Http\Controllers\Admin\Suplier;

Use Auth;
use League\Csv\Writer;
use App\Models\Brand;
use App\Models\Suplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\SuplierDataTable;

class SuplierController extends Controller
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
    public function index(SuplierDataTable $dataTable)
    {
        set_page_meta(__t('supplier'));
        $suplierList = Suplier::get();
        return $dataTable->render('admin.suplier.index', compact('suplierList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('supplier'));

        $brands = Brand::where('status','Active')->get();
        return view('admin.suplier.create', compact('brands'));
    }

    /*
     * This function is worked for
     * Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'brand_name'  => 'required',
            'mobile'      => 'required',
            'email'       => 'nullable', 
        ]);

        $suplier = new Suplier();
        $suplier->name = $request->name;
        $suplier->brand_name = $request->brand_name;
        $suplier->mobile = $request->mobile;
        $suplier->email = $request->email;
        $suplier->created_by = Auth::id();
        $suplier->save();
       
        if (!empty($suplier)) {
            flash(__t('supplier_create_successful'))->success();
        } else {
            flash(__t('supplier_create_failed'))->error();
        }

        return redirect()->route('admin.suplier.index');
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
        set_page_meta(__t('edit') . ' ' . __t('supplier'));
        $suplier = Suplier::find($id);
        $brands = Brand::where('status','Active')->get();
        return response()->json(['suplier'=>$suplier,'brands'=>$brands]);
    }

    public function update(Request $request): RedirectResponse
    {
        
        $request->validate([
            'name'      => 'required', 
            'brand_name'  => 'required',
            'mobile'      => 'required',
            'email'       => 'nullable', 
            'status'    => 'nullable', 
        ]);

        $suplier = Suplier::findorfail($request->id);
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        $suplier->name = $request->name;
        $suplier->brand_name = $request->brand_name;
        $suplier->mobile = $request->mobile;
        $suplier->email = $request->email;
        $suplier->status = $status;
        $suplier->updated_by = Auth::id();
        $suplier->save();
        
        if (!empty($suplier)) {
            flash(__t('supplier_update_successful'))->success();
        } else {
            flash(__t('supplier_update_failed'))->error();
        }

        return redirect()->route('admin.suplier.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Suplier::find($id)->delete()) {
            
            flash(__('custom.supplier_deleted_successfully'))->success();
        } else {
            flash(__('custom.supplier_delete_failed'))->error();
        }

        return redirect()->route('admin.suplier.index');
    }

}
