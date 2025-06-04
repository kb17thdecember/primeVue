<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CMS\Contracts\Services\StorageService;

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
        'display_order',
        'discount_code',
        'image',
        'tag',
        'status',
        'quantity',
        'release_date',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'image' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * @return Attribute
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => collect(json_decode($value, true))
                ->map(fn ($path) => app(StorageService::class)->getImageUrl($path))
                ->toArray(),
            set: fn ($value) => json_encode(collect($value)
                ->map(fn ($url) => app(StorageService::class)->removeBasePath($url))
                ->toArray())
        );
    }
}
