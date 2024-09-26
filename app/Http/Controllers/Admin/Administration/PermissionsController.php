<?php

namespace App\Http\Controllers\Admin\Administration;

use App\DataTables\PermissionDataTable;
use App\Http\Requests\PermissionRequest;
use App\Services\Permission\PermissionService;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    protected $permissionService;

    /**
     * __construct
     *
     * @param  mixed $permissionService
     * @return void
     */
    public function __construct(permissionService $permissionService)
    {
        $this->permissionService  = $permissionService;

        $this->middleware(['permission:Permission List'])->only(['index']);
        $this->middleware(['permission:Add Permission'])->only(['create']);
        $this->middleware(['permission:Edit Permission'])->only(['edit']);
        $this->middleware(['permission:Delete Permission'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(PermissionDataTable $dataTable)
    {
        set_page_meta(__('custom.permissions'));
        return $dataTable->render('admin.administration.permissions.index');
    }

    /**
     * create
     *
     * @return void
     */
    public function create() {
        
        return view('admin.administration.permissions.create');
    }

    public function store(PermissionRequest $request) {

        $data = $request->validated();

        if ($this->permissionService->createOrUpdate($data)) {
            flash(__('custom.permission_create_successful'))->success();
        } else {
            flash(__('custom.permission_create_failed'))->error();
        }

        return redirect()->route('admin.permissions.index');
    }

    public function edit($id) {

        $permission = $this->permissionService->get($id);
        return view('admin.administration.permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->permissionService->createOrUpdate($data, $id)) {
            flash(__('custom.permission_updated_successful'))->success();
        } else {
            flash(__('custom.permission_updated_failed'))->error();
        }

        return redirect()->route('admin.permissions.index');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        if ($this->permissionService->delete($id)) {
            flash(__('custom.permission_deleted_successful'))->success();
        } else {
            flash(__('custom.permission_deleted_failed'))->error();
        }

        return redirect()->route('admin.permissions.index');
    }
}
