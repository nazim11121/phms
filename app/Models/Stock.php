<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function medicineName()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }
}
