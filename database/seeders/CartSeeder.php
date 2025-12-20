<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', true)->where('stock_quantity', '>', 0)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Vui lòng chạy UserSeeder và ProductSeeder trước!');
            return;
        }

        // Mỗi khách hàng có 0-5 sản phẩm trong giỏ hàng
        foreach ($customers as $customer) {
            $cartItemCount = $faker->numberBetween(0, 5);
            $selectedProducts = $products->random($cartItemCount);

            foreach ($selectedProducts as $product) {
                Cart::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'quantity' => $faker->numberBetween(1, min(3, $product->stock_quantity)),
                ]);
            }
        }
    }
}

