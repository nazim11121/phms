<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Services\BaseService;
use App\Models\ProductType;

/**
 * ProductTypeService
 */
class ProductTypeService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(ProductType $model)
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
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    $data[$file_field_name] = $this->uploadFile($data[$file_field_name], $object->$file_field_name);
                }
                return $object->update($data);
            } else {
                $data['created_by'] = Auth::id();
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    $data[$file_field_name] = $this->uploadFile($data[$file_field_name]);
                }
                return $this->model::create($data);
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
