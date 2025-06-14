<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Contracts\Services\StorageService;

class Product extends Model
{
    public $table = 'products';

    protected $fillable = [
        'name',
        'subtitle',
        'description',
        'price',
        'token_qty',
        'image',
        'type',
        'release_date',
        'stripe_product_id',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'image' => 'array',
        'type' => ProductType::class
    ];

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
