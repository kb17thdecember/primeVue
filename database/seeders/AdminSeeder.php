<?php

namespace Database\Seeders;

use App\Enums\AdminStatus;
use App\Enums\Role;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin1',
                'password' => bcrypt('12345678'),
                'status' => AdminStatus::ENABLE,
                'role' => Role::ADMIN,
            ]
        );
    }
}
