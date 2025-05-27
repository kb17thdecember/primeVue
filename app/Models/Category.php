<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Contracts\Services\StorageService;

class Category extends Model
{
    public $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'display_order',
        'description',
        'status',
        'image',
        'created_at',
        'updated_at',
    ];

    /**
     * @return Attribute
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value ? app(StorageService::class)->getImageUrl($value) : null,
            set: static fn ($value) => $value ? app(StorageService::class)->removeBasePath($value) : null
        );
    }
}
