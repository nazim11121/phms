<?php

namespace App\Http\Controllers\Admin\Type;

Use Auth;
use League\Csv\Writer;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\TypeDataTable;

class TypeController extends Controller
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
    public function index(TypeDataTable $dataTable)
    {
        set_page_meta(__t('type'));
        $typeList = Type::get();
        return $dataTable->render('admin.type.index', compact('typeList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('type'));

        return view('admin.type.create');
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

        $type = new Type();
        $type->name = $request->name;
        $type->created_by = Auth::id();
        $type->save();
       
        if (!empty($type)) {
            flash(__t('type_create_successful'))->success();
        } else {
            flash(__t('type_create_failed'))->error();
        }

        return redirect()->route('admin.type.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $typeList = Type::find($id);
        return view('admin.type.view', compact('typeList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('type'));
        $type = Type::find($id);
        return response()->json($type);
    }

    public function update(Request $request): RedirectResponse
    {
        
        $request->validate([
            'name'      => 'required', 
            'status'    => 'nullable', 
        ]);

        $type = Type::findorfail($request->id);
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        $type->name = $request->name;
        $type->status = $status;
        $type->updated_by = Auth::id();
        $type->save();
        
        if (!empty($type)) {
            flash(__t('type_update_successful'))->success();
        } else {
            flash(__t('type_update_failed'))->error();
        }

        return redirect()->route('admin.type.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Type::find($id)->delete()) {
            
            flash(__('custom.type_deleted_successfully'))->success();
        } else {
            flash(__('custom.type_delete_failed'))->error();
        }

        return redirect()->route('admin.type.index');
    }

}
