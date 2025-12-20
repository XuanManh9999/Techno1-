<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $categories = [
            ['name' => 'Điện thoại', 'description' => 'Các loại điện thoại thông minh'],
            ['name' => 'Laptop', 'description' => 'Máy tính xách tay'],
            ['name' => 'Tablet', 'description' => 'Máy tính bảng'],
            ['name' => 'Tai nghe', 'description' => 'Tai nghe và phụ kiện âm thanh'],
            ['name' => 'Đồng hồ thông minh', 'description' => 'Smartwatch và đồng hồ thông minh'],
            ['name' => 'Phụ kiện', 'description' => 'Các phụ kiện điện tử'],
            ['name' => 'Màn hình', 'description' => 'Màn hình máy tính và TV'],
            ['name' => 'Bàn phím & Chuột', 'description' => 'Bàn phím và chuột máy tính'],
            ['name' => 'Loa', 'description' => 'Loa và hệ thống âm thanh'],
            ['name' => 'Camera', 'description' => 'Máy ảnh và camera'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'status' => true,
            ]);
        }

        // Tạo thêm 10 danh mục fake
        for ($i = 0; $i < 10; $i++) {
            $name = $faker->unique()->words(2, true);
            Category::create([
                'name' => ucwords($name),
                'slug' => Str::slug($name),
                'description' => $faker->sentence(10),
                'status' => $faker->boolean(80), // 80% true
            ]);
        }
    }
}
