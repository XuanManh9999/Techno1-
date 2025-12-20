<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', true)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Vui lòng chạy UserSeeder và ProductSeeder trước!');
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        // Tạo 200 đơn hàng
        for ($i = 0; $i < 200; $i++) {
            $customer = $customers->random();
            $status = $faker->randomElement($statuses);
            $paymentStatus = $faker->randomElement($paymentStatuses);
            
            // Nếu đơn đã giao thì phải đã thanh toán
            if ($status === 'delivered') {
                $paymentStatus = 'paid';
            }

            // Tạo order number unique
            $orderNumber = 'ORD' . date('Ymd') . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ORD' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            }

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => $orderNumber,
                'total_amount' => 0, // Sẽ cập nhật sau
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentStatus === 'paid' ? $faker->randomElement(['VNPAY', 'COD']) : null,
                'shipping_name' => $faker->name(),
                'shipping_phone' => $faker->phoneNumber(),
                'shipping_address' => $faker->address(),
                'notes' => $faker->boolean(30) ? $faker->sentence() : null,
                'vnpay_transaction_id' => $paymentStatus === 'paid' && $faker->boolean(50) ? 'TXN' . $faker->numerify('########') : null,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ]);

            // Tạo order items (1-5 sản phẩm mỗi đơn)
            $itemCount = $faker->numberBetween(1, 5);
            $totalAmount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = $faker->numberBetween(1, 3);
                $price = $product->sale_price ?? $product->price;
                $subtotal = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // Cập nhật total_amount
            $order->update(['total_amount' => $totalAmount]);
        }
    }
}

