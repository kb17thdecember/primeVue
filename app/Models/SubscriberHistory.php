<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriberHistory extends Model
{
    use SoftDeletes;

    public $table = 'subscriber_histories';

    protected $fillable = [
        'payment_data',
        'shop_id',
        'product_id',
        'token_qty',
        'price',
        'type',
        'payment_status',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'type' => ProductType::class,
        'payment_status' => PaymentStatus::class,
        'payment_data' => 'object',
    ];

    /**
     * @return HasOne
     */
    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
