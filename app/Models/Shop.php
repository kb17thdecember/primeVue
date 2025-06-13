<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'request_key_flag',
        'api_key',
        'updated_at',
        'created_at',
    ];

    protected $casts = [];

    /**
     * @return HasMany
     */
    public function subscriberHistories(): HasMany
    {
        return $this->hasMany(SubscriberHistory::class);
    }

    /**
     * @return HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'shop_id')
            ->where('role', Role::SHOP->value);
    }

}
