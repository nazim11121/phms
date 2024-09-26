<?php

namespace App\Services\Permission;

use App\Services\BaseService;
use Spatie\Permission\Models\Permission;
// use App\Models\Permission;
use DB;

/**
 * PermissionService
 */
class PermissionService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * getParentPermissions
     *
     * @return void
     */
    public function getParentPermissions()
    {
        return Permission::with('childs')
            ->where('parent_id', null)
            ->get();
    }

    /**
     * createOrUpdate
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function createOrUpdate(array $data, $id = null)
    {
        if ($id) {
            // Update
            $permission = $this->get($id);
        } else {
            // Create
            $permission = new $this->model();
        }

        $module_id=\App\Models\Permission::where('name',$data['group_name'])->get()->pluck('id')->first();

        if($module_id){
            $permission->name = $data['name'];
            $permission->group_name = $data['group_name'];
            $permission->parent_id = $module_id;
            $permission->guard_name = 'web';
            $permission->save();
        }else{
            $permission->name = $data['name'];
            $permission->group_name = $data['group_name'];
            $permission->parent_id = NULL;
            $permission->guard_name = 'web'; 
            $permission->save();
        }
        
        return $permission;
    }
}
