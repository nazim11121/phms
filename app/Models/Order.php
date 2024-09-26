<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // CONST
    public const PENDING = 'Pending';
    public const ACCEPT  = 'Accept';
    public const PROCESSING  = 'Processing';
    public const DELIVERED  = 'Delivered';


    public function orderSku()
    {
        return $this->hasMany(OrderSku::class, 'order_no','order_no');
    }

    public function orderSku2()
    {
        return $this->hasMany(OrderSku::class, 'order_no','order_no')->where('kprint','!=','0');
    }

    public function paymentHistory()
    {
        return $this->hasMany(PaymentHistory::class, 'order_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'order_by','id');
    }
}
