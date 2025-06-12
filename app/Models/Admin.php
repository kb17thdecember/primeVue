<?php

namespace App\Models;

use App\Enums\AdminStatus;
use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
        'shop_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => AdminStatus::class,
        'role' => Role::class,
        'password' => 'hashed',
    ];
}
