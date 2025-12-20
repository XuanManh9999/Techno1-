<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $brands = [
            ['name' => 'Apple', 'description' => 'Thương hiệu Apple'],
            ['name' => 'Samsung', 'description' => 'Thương hiệu Samsung'],
            ['name' => 'Xiaomi', 'description' => 'Thương hiệu Xiaomi'],
            ['name' => 'Oppo', 'description' => 'Thương hiệu Oppo'],
            ['name' => 'Vivo', 'description' => 'Thương hiệu Vivo'],
            ['name' => 'Sony', 'description' => 'Thương hiệu Sony'],
            ['name' => 'LG', 'description' => 'Thương hiệu LG'],
            ['name' => 'Dell', 'description' => 'Thương hiệu Dell'],
            ['name' => 'HP', 'description' => 'Thương hiệu HP'],
            ['name' => 'Asus', 'description' => 'Thương hiệu Asus'],
            ['name' => 'Lenovo', 'description' => 'Thương hiệu Lenovo'],
            ['name' => 'Acer', 'description' => 'Thương hiệu Acer'],
            ['name' => 'MSI', 'description' => 'Thương hiệu MSI'],
            ['name' => 'Razer', 'description' => 'Thương hiệu Razer'],
            ['name' => 'Logitech', 'description' => 'Thương hiệu Logitech'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'status' => true,
            ]);
        }

        // Tạo thêm 10 thương hiệu fake
        for ($i = 0; $i < 10; $i++) {
            $name = $faker->unique()->company();
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->sentence(10),
                'status' => $faker->boolean(85), // 85% true
            ]);
        }
    }
}
