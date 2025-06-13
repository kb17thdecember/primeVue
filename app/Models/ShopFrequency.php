<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopFrequency extends Model
{
    public $table = 'shop_frequencies';

    public $timestamps = false;
    protected $fillable = [
        'api_key',
        'date',
        'daily_count',
    ];

    protected $casts = [];
}
