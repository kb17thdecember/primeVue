<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopFrequency extends Model
{
    public $table = 'shop_frequencies';

    public $timestamps = false;
    protected $fillable = [
        'api_key',
        'date',
        'daily_count',
        'shop_id'
    ];

    protected $casts = [];

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
