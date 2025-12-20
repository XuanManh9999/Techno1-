<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->warn('Vui lòng chạy CategorySeeder và BrandSeeder trước!');
            return;
        }

        $productNames = [
            // Điện thoại
            ['iPhone 15 Pro Max 256GB', 'Điện thoại', 'Apple', 29990000, 27990000],
            ['Samsung Galaxy S24 Ultra 512GB', 'Điện thoại', 'Samsung', 28990000, 26990000],
            ['Xiaomi 14 Pro', 'Điện thoại', 'Xiaomi', 19990000, 17990000],
            ['Oppo Find X7', 'Điện thoại', 'Oppo', 18990000, 16990000],
            ['Vivo X100 Pro', 'Điện thoại', 'Vivo', 17990000, 15990000],
            
            // Laptop
            ['MacBook Pro 14 inch M3', 'Laptop', 'Apple', 49990000, null],
            ['Dell XPS 15', 'Laptop', 'Dell', 39990000, 36990000],
            ['HP Spectre x360', 'Laptop', 'HP', 34990000, null],
            ['Asus ROG Strix G16', 'Laptop', 'Asus', 32990000, 29990000],
            ['Lenovo ThinkPad X1', 'Laptop', 'Lenovo', 31990000, null],
            
            // Tablet
            ['iPad Pro 12.9 inch M2', 'Tablet', 'Apple', 29990000, null],
            ['Samsung Galaxy Tab S9', 'Tablet', 'Samsung', 19990000, 17990000],
            ['Xiaomi Pad 6 Pro', 'Tablet', 'Xiaomi', 8990000, 7990000],
            
            // Tai nghe
            ['AirPods Pro 2', 'Tai nghe', 'Apple', 6990000, 6490000],
            ['Sony WH-1000XM5', 'Tai nghe', 'Sony', 8990000, 7990000],
            ['Samsung Galaxy Buds2 Pro', 'Tai nghe', 'Samsung', 4990000, 4490000],
            
            // Đồng hồ
            ['Apple Watch Series 9', 'Đồng hồ thông minh', 'Apple', 10990000, null],
            ['Samsung Galaxy Watch 6', 'Đồng hồ thông minh', 'Samsung', 7990000, 6990000],
            
            // Phụ kiện
            ['Sạc không dây MagSafe', 'Phụ kiện', 'Apple', 1990000, 1490000],
            ['Ốp lưng iPhone 15', 'Phụ kiện', 'Apple', 990000, 790000],
        ];

        foreach ($productNames as $productData) {
            $category = $categories->where('name', $productData[1])->first();
            $brand = $brands->where('name', $productData[2])->first();

            if ($category && $brand) {
                Product::create([
                    'name' => $productData[0],
                    'slug' => Str::slug($productData[0]),
                    'description' => $faker->paragraph(3),
                    'short_description' => $faker->sentence(10),
                    'price' => $productData[3],
                    'sale_price' => $productData[4],
                    'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    'stock_quantity' => $faker->numberBetween(0, 200),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'status' => true,
                    'featured' => $faker->boolean(30), // 30% featured
                ]);
            }
        }

        // Tạo 100 sản phẩm fake ngẫu nhiên
        for ($i = 0; $i < 100; $i++) {
            $category = $categories->random();
            $brand = $brands->random();
            $name = $faker->words(3, true);
            $price = $faker->numberBetween(500000, 50000000);
            $hasSale = $faker->boolean(40); // 40% có sale

            Product::create([
                'name' => ucwords($name),
                'slug' => Str::slug($name) . '-' . $i,
                'description' => $faker->paragraph(5),
                'short_description' => $faker->sentence(15),
                'price' => $price,
                'sale_price' => $hasSale ? $price * 0.8 : null, // Giảm 20% nếu có sale
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'stock_quantity' => $faker->numberBetween(0, 300),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'status' => $faker->boolean(90), // 90% active
                'featured' => $faker->boolean(20), // 20% featured
            ]);
        }
    }
}
