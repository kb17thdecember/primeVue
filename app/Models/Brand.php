<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CMS\Contracts\Services\StorageService;

class Brand extends Model
{
    public $table = 'brands';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'display_order',
        'description',
        'shop_id',
        'status',
        'logo',
        'created_at',
        'updated_at',
    ];

    public function logo(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value ? app(StorageService::class)->getImageUrl($value) : null,
            set: static fn ($value) => $value ? app(StorageService::class)->removeBasePath($value) : null
        );
    }
}
