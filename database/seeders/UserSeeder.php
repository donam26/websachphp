<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@bookstore.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'full_name' => 'Quản trị viên',
                'phone_number' => '0901234567',
                'address' => '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@bookstore.com'],
            [
                'username' => 'demo',
                'password' => Hash::make('password'),
                'full_name' => 'Nguyễn Văn A',
                'phone_number' => '0987654321',
                'address' => '456 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'role' => User::ROLE_USER,
            ]
        );
    }
}
