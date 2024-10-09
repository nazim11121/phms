<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function medicine()
    {
        return $this->belongsTo(MedicineAdd::class,'medicine_id');
    }
}
