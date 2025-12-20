<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $orders = Order::where('payment_status', 'paid')->get();

        if ($orders->isEmpty()) {
            $this->command->warn('Vui lòng chạy OrderSeeder trước!');
            return;
        }

        foreach ($orders as $order) {
            // Chỉ tạo payment cho đơn đã thanh toán
            if ($order->payment_status === 'paid' && !$order->payment) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method ?? 'VNPAY',
                    'amount' => $order->total_amount,
                    'status' => 'success',
                    'transaction_id' => $order->vnpay_transaction_id ?? 'TXN' . $faker->numerify('########'),
                    'vnpay_response_code' => '00',
                    'vnpay_transaction_no' => $faker->numerify('########'),
                    'vnpay_response_data' => [
                        'vnp_ResponseCode' => '00',
                        'vnp_TransactionNo' => $faker->numerify('########'),
                        'vnp_TxnRef' => $order->order_number,
                    ],
                    'created_at' => $order->created_at,
                ]);
            }
        }

        // Tạo một số payment failed cho đơn hàng failed
        $failedOrders = Order::where('payment_status', 'failed')->limit(10)->get();
        foreach ($failedOrders as $order) {
            if (!$order->payment) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'VNPAY',
                    'amount' => $order->total_amount,
                    'status' => 'failed',
                    'vnpay_response_code' => $faker->randomElement(['07', '09', '10', '11']),
                    'vnpay_response_data' => [
                        'vnp_ResponseCode' => $faker->randomElement(['07', '09', '10', '11']),
                        'vnp_TxnRef' => $order->order_number,
                    ],
                    'created_at' => $order->created_at,
                ]);
            }
        }
    }
}

