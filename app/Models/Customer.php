<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public const Active = 'Active';
    public const Inactive = 'Inactive';

    protected $guarded = [];
}
