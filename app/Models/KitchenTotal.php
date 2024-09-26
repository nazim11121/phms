<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenTotal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kitchen()
    {
        return $this->belongsTo(KitchenTotal::class, 'kitchen_id', 'kitchen_id');
    }
}
