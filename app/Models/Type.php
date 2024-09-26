<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $guarded = [];

    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';
}
