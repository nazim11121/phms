<?php

namespace App\Services\Product;

use App\Models\DraftInvoiceItem;
use App\Models\InvoiceItem;
use App\Models\ProductStock;
use App\Models\SystemSettings;
use Illuminate\Database\Eloquent\Builder;
use Throwable;
use App\Models\Product;
use App\Services\BaseService;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Utils\FileUploadService;

/**
 * ProductService
 */
class ProductService extends BaseService
{
    protected $fileUploadService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $model = new Product();
        parent::__construct($model);
        $this->fileUploadService = app(FileUploadService::class);
    }

    public function wareHouseWiseProducts($with = null, $warehouse)
    {
        return $this->model
            ->newQuery()
            ->with([$with => function($q) use($warehouse){
                $q->where('warehouse_id', $warehouse->id);
            }])
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('stock', function (Builder $builder) use ($warehouse) {
                $builder->where('warehouse_id', $warehouse->id)->where('quantity', '>', 0);
            })->get();
    }

    public function wareHouseWiseAllProductStocks($with = null, $warehouse)
    {
        return ProductStock::
            when(Auth::guard('customer')->check(), function($q){
                $q->whereHas('product', function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                });
            })
            ->with($with)
            ->where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)
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
        try {
            if ($id) {
                try {
                    DB::beginTransaction();

                    $data['testing'] = array_key_exists('testing', $data) ? $data['testing'] : 0;
                    $data['blending'] = array_key_exists('blending', $data) ? $data['blending'] : 0;
                    $data['batch_conversion'] = array_key_exists('batch_conversion', $data) ? $data['batch_conversion'] : 0;
                    $data['packet_conversion'] = array_key_exists('packet_conversion', $data) ? $data['packet_conversion'] : 0;
                    $data['new_submodule'] = array_key_exists('new_submodule', $data) ? $data['new_submodule'] : 0;
                    $data['updated_by'] = Auth::id();

                    $object = $this->model->findOrFail($id);

                    if (isset($data['thumb']) && $data['thumb'] != null) {
                        $data['thumb'] = $this->uploadFile($data['thumb'], $object->thumb);
                    }

                    if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
                        $data['barcode_image'] = $this->processBarcodeImage($data['sku'], $data['barcode'], $data['barcode_image'], $object->barcode_image);
                    }
                    
                    // Update product
                    $data['product_type']= json_encode($data['product_type']);
                    $data['shape_id'] = json_encode($data['shape_id']);
                    $data['measurement_unit_id'] = json_encode($data['measurement_unit_id']);
                    $object->update($data);

                    DB::commit();
                    return true;
                } catch (Throwable $th) {
                    DB::rollBack();
                }
            } else {
                try {
                    DB::beginTransaction();
                    $data['created_by'] = Auth::id();
                    if (isset($data['thumb']) && $data['thumb'] != null) {
                        $data['thumb'] = $this->uploadFile($data['thumb']);
                    }

                    if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
                        $data['barcode_image'] = $this->processBarcodeImage($data['sku'], $data['barcode'], $data['barcode_image']);
                    }

                    // Store product
                    $data['product_type']= json_encode($data['product_type']);
                    $data['shape_id'] = json_encode($data['shape_id']);
                    $data['measurement_unit_id'] = json_encode($data['measurement_unit_id']);

                    $product = $this->model::create($data);
                    
                    DB::commit();
                    return $product;
                } catch (Throwable $th) {
                    DB::rollBack();
                }
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * processBarcodeImage
     *
     * @param  mixed $base64image
     * @param  mixed $old_name
     * @return void
     */
    public function processBarcodeImage($sku, $barcode, $base64image, $old_name = null)
    {
        // Store barcode image
        try {
            if ($old_name) {
                // Delete image
                try {
                    Storage::disk(config('filesystems.default'))->delete($this->model::BARCODE_STORE_PATH . '/' . $old_name);
                } catch (Throwable $th) {
                    //throw $th;
                }
            }
            $name = $sku . '_' . $barcode . '.png';
            $img = substr($base64image, strpos($base64image, ",") + 1);
            Storage::disk(config('filesystems.default'))->put($this->model::BARCODE_STORE_PATH . '/' . $name, base64_decode($img));
            return $name;
        } catch (Throwable $th) {
        }
    }

    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $data = $this->model::findOrFail($id);
            // $product_stock_id = ProductStock::where('product_id',$id)-get()->pluck('id')->first();
            // $data = ProductStock::findOrFail($product_stock_id);
            // Delete barcode
            try {
                Storage::disk(config('filesystems.default'))->delete($this->model::BARCODE_STORE_PATH . '/' . $data->barcode_image);
            } catch (Throwable $th) {
                //throw $th;
            }
            // Delete Product
            return $data->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * getByBarcode
     *
     * @param  mixed $barcode
     * @return void
     */
    public function getByBarcode($barcode)
    {
        return $this->model::where('barcode', $barcode)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })->first();
    }
    public function getProductStockByBarcode($barcode)
    {
        return ProductStock::
        when(Auth::guard('customer')->check(), function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->whereHas('product', function ($q) use ($barcode){
            $q->where('barcode', $barcode);
        })
        ->first();
    }

    /**
     * getByCategory
     *
     * @param  mixed $category_id
     * @return void
     */
    public function getByCategory($category_id, $with = [])
    {
        try {

            return $this->model::with($with)
                ->when(Auth::guard('customer')->check(), function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                })
                ->whereHas('warehouseStockQty', function ($q){
                $q->where('quantity', '>', 0);
            })->where('category_id', $category_id)->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }
    public function getProductStockByCategory($category_id,$warehouse_id, $with = [])
    {
        try {

            return ProductStock::
            when(Auth::guard('customer')->check(), function($q){
                $q->whereHas('product', function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                });
            })
            ->whereHas('product', function ($q) use ($category_id){
                $q->where('category_id', $category_id);
            })
                ->with($with)
                ->where('warehouse_id', $warehouse_id)
                ->where('quantity', '>', 0)
                ->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * getProductBySearch
     *
     * @param  mixed $q
     * @param  mixed $select
     * @param  mixed $with
     * @return void
     */
    public function getProductBySearch($q, $select = '*', $with = [])
    {
        return $this->model->select($select)->with($with)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('warehouseStockQty', function ($q){
            $q->where('quantity', '>', 0);
        })->where('sku', 'like', '%' . $q . '%')->get();
    }

    /**
     * getProductByNameSku
     *
     * @param  mixed $q
     * @param  mixed $select
     * @param  mixed $with
     * @return void
     */
    public function getProductByNameSku($q, $select = '*', $with = [])
    {
        $q = strtolower($q);
        return $this->model::select($select)
            ->with($with)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('warehouseStockQty', function ($q){
                $q->where('quantity', '>', 0);
            })
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')
            ->limit(10)
            ->get();
    }
    public function getProductStockByNameSku($search_q, $warehouse_id, $select = '*', $with = [])
    {
        $search_q = strtolower($search_q);

        return ProductStock::
        when(Auth::guard('customer')->check(), function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->whereHas('product', function ($q) use ($search_q){
            $q->where('name', 'like', '%' . $search_q . '%');
            $q->orWhere('sku', 'like', '%' . $search_q . '%');
            $q->orWhere('barcode', 'like', '%' . $search_q . '%');
        })
        ->with($with)
        ->where('warehouse_id', $warehouse_id)
        ->where('quantity', '>', 0)
        ->get();
    }

    public function lowStockList()
    {
        $products = Product::query()
            ->with('allStock')
            ->where('status', Product::STATUS_ACTIVE)
            ->get(['id', 'name', 'sku', 'barcode', 'price', 'stock_alert_quantity', 'thumb']);

        $productStockList = [];

        foreach ($products as $product){
            if (optional($product->allStock)->sum('quantity') < $product->stock_alert_quantity){
                $productStockList[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'price' => $product->price,
                    'image' =>  getStorageImage(Product::FILE_STORE_PATH, $product->thumb),
                    'stock' => optional($product->allStock)->sum('quantity') ?? 0,
                    'alert_quantity' => $product->stock_alert_quantity
                ];
            }
        }

        return [

            'total_product' => count($productStockList),
            'productStockList' => $productStockList

        ];
    }

    public function skuSettings()
    {
        $productSettings = SystemSettings::query()->where('settings_key', 'product_setting')->first();

        if ($productSettings){
            $productMaxId = (Product::query()->max('id')+1);

            $generatedSKU = $productSettings->settings_value['sku.prefix'].make8digits($productMaxId).$productSettings->settings_value['sku.suffix'];

            return [
                'auto' => $productSettings->settings_value['sku.auto'],
                'editable' => $productSettings->settings_value['sku.editable'],
                'generated_sku' => $generatedSKU
            ];
        }
        return [
            'auto' => null,
            'editable'=> null,
            'generated_sku' => null
        ];
    }

    public function productAllWarehouseStock()
    {
        return $this->model
            ->newQuery()
            ->with('allStock.attribute', 'allStock.attributeItem', 'category')
            ->orderByDesc('id')
            ->get(['name', 'sku', 'barcode', 'price', 'category_id', 'id', 'stock_alert_quantity']);
    }
}
