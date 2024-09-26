<?php

namespace App\Http\Controllers\Admin\Group;

Use Auth;
use League\Csv\Writer;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\GroupDataTable;

class GroupController extends Controller
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
    public function index(GroupDataTable $dataTable)
    {
        set_page_meta(__t('group'));
        $groupList = Group::get();
        return $dataTable->render('admin.group.index', compact('groupList'));
    }

    /*
     * This function is worked for
     * create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('group'));

        return view('admin.group.create');
    }

    /*
     * This function is worked for
     * Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => 'required', 
        ]);

        $group = new Group();
        $group->name = $request->name;
        $group->created_by = Auth::id();
        $group->save();
       
        if (!empty($group)) {
            flash(__t('group_create_successful'))->success();
        } else {
            flash(__t('group_create_failed'))->error();
        }

        return redirect()->route('admin.group.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $groupList = Group::find($id);
        return view('admin.group.view', compact('groupList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('group'));
        $group = Group::find($id);
        return response()->json($group);
    }

    public function update(Request $request): RedirectResponse
    {
        
        $request->validate([
            'name'  => 'required', 
            'status'       => 'nullable', 
        ]);

        $group = Group::findorfail($request->id);
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        $group->name = $request->name;
        $group->status = $status;
        $group->updated_by = Auth::id();
        $group->save();
        
        if (!empty($group)) {
            flash(__t('group_update_successful'))->success();
        } else {
            flash(__t('group_update_failed'))->error();
        }

        return redirect()->route('admin.group.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Group::find($id)->delete()) {
            
            flash(__('custom.group_deleted_successfully'))->success();
        } else {
            flash(__('custom.group_delete_failed'))->error();
        }

        return redirect()->route('admin.group.index');
    }

}
