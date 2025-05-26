<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
