<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Services\BaseService;
use App\Models\ProductRole;

/**
 * ProductRoleService
 */
class ProductRoleService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(ProductRole $model)
    {
        parent::__construct($model);
    }

    /**
     * getParents
     *
     * @param  mixed $with
     * @return void
     */
    public function createOrUpdateWithFile(array $data, $file_field_name, $id = null)
    {
        try {
            if ($id) {

                $data['updated_by'] = Auth::id();
                $object = $this->model->findOrFail($id);
                return $object->update($data);
            } else {
                $data['created_by'] = Auth::id();
                return $this->model::create($data);
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
