<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product
 */
class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'product_type',
        'name',
        'sku',
        'barcode',
        'barcode_image',
        'packet_id',
        'shape_id',
        'price',
        'measurement_unit_id',
        'desc',
        'thumb',
        'status',
        'tax_status',
        'custom_tax',
        'created_by',
        'updated_by',
        'stock',
        'testing',
        'blending',
        'batch_conversion',
        'packet_conversion',
        'new_submodule',
        'available_for',
        'customer_buying_price',
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['thumb_url'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const TAX_INCLUDED = 'included';
    public const TAX_EXCLUDED = 'excluded';

    public const FILE_STORE_PATH = 'products';
    public const BARCODE_STORE_PATH = 'product_barcodes';

    public const SALE_AVAILABLE_FOR = [
        'all'       => 'all',
        'customer'  => 'customer',
        'warehouse' => 'warehouse',
    ];


    /**
     * totalSale
     *
     * @return void
     */
    public function totalSale()
    {
        return InvoiceItem::where('product_id', $this->id)->sum('sub_total');
    }
    public function totalSaleQty()
    {
        return InvoiceItem::where('product_id', $this->id)->sum('quantity');
    }

    /**
     * getThumbUrlAttribute
     *
     * @return void
     */
    public function getThumbUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->thumb);
    }

    // RELATIONS
    /**
     * category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function product_type_id()
    {
        return $this->belongsTo(ProductType::class, 'product_type','id');
    }

    public function shapes()
    {
        return $this->belongsTo(Shape::class, 'shape_id','id');
    }

    /**
     * attributes
     *
     * @return void
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * stock
     *
     * @return void
     */
    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }
    public function productStock()
    {
        return $this->hasOne(ProductStock::class);
    }

    /**
     * warehouseStock
     *
     * @param  mixed $warehouse
     * @return void
     */
    public function warehouseStock($warehouse)
    {
        return $this->stock()
            ->where('warehouse_id', $warehouse)
            ->first()
            ->quantity ?? 0;
    }

    public function warehouseStockQty()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }

    public function weight_unit()
    {
        return $this->belongsTo(WeightUnit::class, 'weight_unit_id', 'id');
    }

    public function allStock()
    {
        return $this->hasMany(ProductStock::class);
    }
}
