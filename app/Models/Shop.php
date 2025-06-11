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
        'subdomain',
        'province',
        'prefecture',
        'town',
        'address',
        'phone_number',
        'status',
        'api_key',
        'channel_access_token',
        'channel_secret',
        'updated_at',
        'created_at',
    ];

    protected $casts = [];
}
