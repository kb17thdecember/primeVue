<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'description',
        'price',
        'discount',
        'image',
        'hot_product',
        'status',
        'quantity',
        'release_date',
        'updated_at',
    ];
}
