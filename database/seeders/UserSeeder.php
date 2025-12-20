<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@techno1.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'role' => User::ROLE_ADMIN,
        ]);

        // Sample customer users
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => '456 Đường XYZ, Quận 2, TP.HCM',
            'role' => User::ROLE_CUSTOMER,
        ]);

        // Tạo 50 khách hàng fake
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'role' => User::ROLE_CUSTOMER,
            ]);
        }

        // Tạo 5 admin fake
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'role' => User::ROLE_ADMIN,
            ]);
        }
    }
}
