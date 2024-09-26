<?php

namespace App\Http\Controllers\Admin\Brand;

Use Auth;
use League\Csv\Writer;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\BrandDataTable;

class BrandController extends Controller
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
    public function index(BrandDataTable $dataTable)
    {
        set_page_meta(__t('brand'));
        $brandList = Brand::get();
        return $dataTable->render('admin.brand.index', compact('brandList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('brand'));

        return view('admin.brand.create');
    }

    /*
     * This function is worked for
     * Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required', 
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->created_by = Auth::id();
        $brand->save();
       
        if (!empty($brand)) {
            flash(__t('brand_create_successful'))->success();
        } else {
            flash(__t('brand_create_failed'))->error();
        }

        return redirect()->route('admin.brand.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $brandList = Brand::find($id);
        return view('admin.brand.view', compact('brandList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('brand'));
        $brand = Brand::find($id);
        return response()->json($brand);
    }

    public function update(Request $request): RedirectResponse
    {
        
        $request->validate([
            'name'      => 'required', 
            'status'    => 'nullable', 
        ]);

        $brand = Brand::findorfail($request->id);
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        $brand->name = $request->name;
        $brand->status = $status;
        $brand->updated_by = Auth::id();
        $brand->save();
        
        if (!empty($brand)) {
            flash(__t('brand_update_successful'))->success();
        } else {
            flash(__t('brand_update_failed'))->error();
        }

        return redirect()->route('admin.brand.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Brand::find($id)->delete()) {
            
            flash(__('custom.brand_deleted_successfully'))->success();
        } else {
            flash(__('custom.brand_delete_failed'))->error();
        }

        return redirect()->route('admin.brand.index');
    }

}
