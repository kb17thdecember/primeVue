<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Enums\TokenStockStatus;
use Illuminate\Database\Eloquent\Model;

class ShopTokenStocks extends Model
{
    public $table = 'shop_token_stocks';

    public $timestamps = false;

    protected $fillable = [
        'shop_id',
        'token_qty',
        'product_id',
        'available_start_date',
        'available_end_date',
        'status',
    ];

    protected $casts = [
        'status' => TokenStockStatus::class,
        'available_start_date' => 'datetime',
        'available_end_date' => 'datetime',
    ];

    public function shop()
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
