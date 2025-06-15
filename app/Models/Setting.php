<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $table = 'settings';

    public $timestamps = false;

    protected $fillable = [
        'remaining',
        'mail_subject',
        'mail_template',
        'updated_at',
        'created_at',
    ];

    protected $casts = [];
}
