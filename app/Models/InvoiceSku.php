<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSku extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function medicine(){
        return $this->belongsTo(MedicineAdd::class,'product_id','id');
    }
}
