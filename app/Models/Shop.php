<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public $table = 'shops';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'admin_id',
        'province',
        'prefecture',
        'town',
        'address',
        'phone_number',
        'status',
        'api_key',
        'updated_at',
        'created_at',
    ];

    protected $casts = [];
}
