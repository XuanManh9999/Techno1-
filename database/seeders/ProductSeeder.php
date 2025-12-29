<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
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

        $colors = ['Đỏ', 'Xanh', 'Đen', 'Trắng', 'Vàng', 'Tím', 'Hồng', 'Xám', 'Bạc', 'Xanh lá'];
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $storageSizes = ['64GB', '128GB', '256GB', '512GB', '1TB'];
        
        foreach ($productNames as $productData) {
            $category = $categories->where('name', $productData[1])->first();
            $brand = $brands->where('name', $productData[2])->first();

            if ($category && $brand) {
                $product = Product::create([
                    'name' => $productData[0],
                    'slug' => Str::slug($productData[0]),
                    'description' => $faker->paragraph(3),
                    'short_description' => $faker->sentence(10),
                    'price' => $productData[3],
                    'sale_price' => $productData[4],
                    'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    'stock_quantity' => $faker->numberBetween(0, 200),
                    'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'status' => true,
                    'featured' => $faker->boolean(30), // 30% featured
                ]);

                // Tạo variants ĐẦY ĐỦ cho TẤT CẢ sản phẩm
                // Xác định số lượng variants dựa trên loại sản phẩm
                if (in_array($category->name, ['Điện thoại', 'Tablet'])) {
                    // Điện thoại và Tablet: Tạo variants với nhiều màu và dung lượng
                    $variantColors = $faker->randomElements($colors, rand(3, 5)); // 3-5 màu
                    $variantStorages = $faker->randomElements($storageSizes, rand(2, 4)); // 2-4 dung lượng
                    $isFirstDefault = true;
                    $variantIndex = 0;
                    
                    foreach ($variantColors as $color) {
                        foreach ($variantStorages as $storage) {
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $product->sku . '-' . ($variantIndex + 1),
                                'name' => $product->name . ' - ' . $color . ' - ' . $storage,
                                'attributes' => [
                                    'Màu sắc' => $color,
                                    'Dung lượng' => $storage,
                                ],
                                'price' => $faker->boolean(20) ? $productData[3] + $faker->numberBetween(-3000000, 3000000) : null,
                                'sale_price' => $productData[4] ? ($productData[4] + $faker->numberBetween(-1500000, 1500000)) : null,
                                'stock_quantity' => $faker->numberBetween(10, 200),
                                'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                                'is_default' => $isFirstDefault,
                                'status' => true,
                                'sort_order' => $variantIndex,
                            ]);
                            
                            $isFirstDefault = false;
                            $variantIndex++;
                        }
                    }
                } elseif ($category->name === 'Laptop') {
                    // Laptop: Tạo variants với màu và cấu hình RAM/SSD
                    $variantColors = $faker->randomElements(['Bạc', 'Xám', 'Đen', 'Xanh'], rand(2, 3));
                    $ramOptions = ['8GB', '16GB', '32GB'];
                    $ssdOptions = ['256GB', '512GB', '1TB'];
                    $isFirstDefault = true;
                    $variantIndex = 0;
                    
                    foreach ($variantColors as $color) {
                        $ram = $faker->randomElement($ramOptions);
                        $ssd = $faker->randomElement($ssdOptions);
                        
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $product->sku . '-' . ($variantIndex + 1),
                            'name' => $product->name . ' - ' . $color . ' - ' . $ram . ' RAM - ' . $ssd . ' SSD',
                            'attributes' => [
                                'Màu sắc' => $color,
                                'RAM' => $ram,
                                'Ổ cứng' => $ssd,
                            ],
                            'price' => $productData[3] + $faker->numberBetween(-5000000, 5000000),
                            'sale_price' => $productData[4] ? ($productData[4] + $faker->numberBetween(-3000000, 3000000)) : null,
                            'stock_quantity' => $faker->numberBetween(5, 100),
                            'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                            'is_default' => $isFirstDefault,
                            'status' => true,
                            'sort_order' => $variantIndex,
                        ]);
                        
                        $isFirstDefault = false;
                        $variantIndex++;
                    }
                } else {
                    // Các sản phẩm khác: Tạo variants với màu và kích thước
                    $variantColors = $faker->randomElements($colors, rand(3, 6)); // 3-6 màu
                    $variantSizes = $faker->randomElements($sizes, rand(2, 4)); // 2-4 size
                    $isFirstDefault = true;
                    $variantIndex = 0;
                    
                    foreach ($variantColors as $color) {
                        foreach ($variantSizes as $size) {
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $product->sku . '-' . ($variantIndex + 1),
                                'name' => $product->name . ' - ' . $color . ' - ' . $size,
                                'attributes' => [
                                    'Màu sắc' => $color,
                                    'Kích thước' => $size,
                                ],
                                'price' => $faker->boolean(20) ? $productData[3] + $faker->numberBetween(-1000000, 1000000) : null,
                                'sale_price' => $productData[4] ? ($productData[4] + $faker->numberBetween(-500000, 500000)) : null,
                                'stock_quantity' => $faker->numberBetween(10, 150),
                                'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                                'is_default' => $isFirstDefault,
                                'status' => true,
                                'sort_order' => $variantIndex,
                            ]);
                            
                            $isFirstDefault = false;
                            $variantIndex++;
                        }
                    }
                }
            }
        }

        // Tạo 100 sản phẩm fake ngẫu nhiên
        for ($i = 0; $i < 100; $i++) {
            $category = $categories->random();
            $brand = $brands->random();
            $name = $faker->words(3, true);
            $price = $faker->numberBetween(500000, 50000000);
            $hasSale = $faker->boolean(40); // 40% có sale

            $product = Product::create([
                'name' => ucwords($name),
                'slug' => Str::slug($name) . '-' . $i,
                'description' => $faker->paragraph(5),
                'short_description' => $faker->sentence(15),
                'price' => $price,
                'sale_price' => $hasSale ? $price * 0.8 : null, // Giảm 20% nếu có sale
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'stock_quantity' => $faker->numberBetween(0, 300),
                'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'status' => $faker->boolean(90), // 90% active
                'featured' => $faker->boolean(20), // 20% featured
            ]);

            // Tạo variants ĐẦY ĐỦ cho TẤT CẢ sản phẩm ngẫu nhiên
            if (in_array($category->name, ['Điện thoại', 'Tablet'])) {
                // Điện thoại và Tablet: Tạo variants với nhiều màu và dung lượng
                $variantColors = $faker->randomElements($colors, rand(2, 4));
                $variantStorages = $faker->randomElements($storageSizes, rand(2, 3));
                $isFirstDefault = true;
                $variantIndex = 0;
                
                foreach ($variantColors as $color) {
                    foreach ($variantStorages as $storage) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $product->sku . '-' . ($variantIndex + 1),
                            'name' => $product->name . ' - ' . $color . ' - ' . $storage,
                            'attributes' => [
                                'Màu sắc' => $color,
                                'Dung lượng' => $storage,
                            ],
                            'price' => $faker->boolean(20) ? $price + $faker->numberBetween(-2000000, 2000000) : null,
                            'sale_price' => $hasSale ? ($price * 0.8 + $faker->numberBetween(-1000000, 1000000)) : null,
                            'stock_quantity' => $faker->numberBetween(5, 150),
                            'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                            'is_default' => $isFirstDefault,
                            'status' => true,
                            'sort_order' => $variantIndex,
                        ]);
                        
                        $isFirstDefault = false;
                        $variantIndex++;
                    }
                }
            } elseif ($category->name === 'Laptop') {
                // Laptop: Tạo variants với màu và cấu hình
                $variantColors = $faker->randomElements(['Bạc', 'Xám', 'Đen'], rand(2, 3));
                $ramOptions = ['8GB', '16GB'];
                $ssdOptions = ['256GB', '512GB'];
                $isFirstDefault = true;
                $variantIndex = 0;
                
                foreach ($variantColors as $color) {
                    $ram = $faker->randomElement($ramOptions);
                    $ssd = $faker->randomElement($ssdOptions);
                    
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $product->sku . '-' . ($variantIndex + 1),
                        'name' => $product->name . ' - ' . $color . ' - ' . $ram . ' RAM - ' . $ssd . ' SSD',
                        'attributes' => [
                            'Màu sắc' => $color,
                            'RAM' => $ram,
                            'Ổ cứng' => $ssd,
                        ],
                        'price' => $price + $faker->numberBetween(-3000000, 3000000),
                        'sale_price' => $hasSale ? ($price * 0.8 + $faker->numberBetween(-2000000, 2000000)) : null,
                        'stock_quantity' => $faker->numberBetween(3, 80),
                        'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                        'is_default' => $isFirstDefault,
                        'status' => true,
                        'sort_order' => $variantIndex,
                    ]);
                    
                    $isFirstDefault = false;
                    $variantIndex++;
                }
            } else {
                // Các sản phẩm khác: Tạo variants với màu và kích thước
                $variantColors = $faker->randomElements($colors, rand(2, 5));
                $variantSizes = $faker->randomElements($sizes, rand(2, 4));
                $isFirstDefault = true;
                $variantIndex = 0;
                
                foreach ($variantColors as $color) {
                    foreach ($variantSizes as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $product->sku . '-' . ($variantIndex + 1),
                            'name' => $product->name . ' - ' . $color . ' - ' . $size,
                            'attributes' => [
                                'Màu sắc' => $color,
                                'Kích thước' => $size,
                            ],
                            'price' => $faker->boolean(20) ? $price + $faker->numberBetween(-500000, 500000) : null,
                            'sale_price' => $hasSale ? ($price * 0.8 + $faker->numberBetween(-300000, 300000)) : null,
                            'stock_quantity' => $faker->numberBetween(5, 120),
                            'image' => 'https://picsum.photos/800/800?random=' . rand(1, 1000),
                            'is_default' => $isFirstDefault,
                            'status' => true,
                            'sort_order' => $variantIndex,
                        ]);
                        
                        $isFirstDefault = false;
                        $variantIndex++;
                    }
                }
            }
        }
    }
}
