<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Tạo các coupon mẫu
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'type' => Coupon::TYPE_PERCENTAGE,
                'value' => 10,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => 500000,
                'usage_limit' => 1000,
                'used_count' => $faker->numberBetween(0, 500),
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now()->subDays(30),
                'expires_at' => Carbon::now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE50K',
                'name' => 'Tiết kiệm 50K',
                'description' => 'Giảm 50.000 VNĐ cho đơn hàng từ 500.000 VNĐ',
                'type' => Coupon::TYPE_FIXED,
                'value' => 50000,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => null,
                'usage_limit' => 500,
                'used_count' => $faker->numberBetween(0, 200),
                'usage_limit_per_user' => 2,
                'starts_at' => Carbon::now()->subDays(15),
                'expires_at' => Carbon::now()->addDays(45),
                'is_active' => true,
            ],
            [
                'code' => 'VIP20',
                'name' => 'Khách hàng VIP',
                'description' => 'Giảm 20% cho khách hàng VIP',
                'type' => Coupon::TYPE_PERCENTAGE,
                'value' => 20,
                'min_purchase_amount' => 1000000,
                'max_discount_amount' => 2000000,
                'usage_limit' => 200,
                'used_count' => $faker->numberBetween(0, 100),
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now()->subDays(10),
                'expires_at' => Carbon::now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'FLASH100K',
                'name' => 'Flash Sale',
                'description' => 'Giảm 100.000 VNĐ cho đơn hàng từ 1.000.000 VNĐ',
                'type' => Coupon::TYPE_FIXED,
                'value' => 100000,
                'min_purchase_amount' => 1000000,
                'max_discount_amount' => null,
                'usage_limit' => 300,
                'used_count' => $faker->numberBetween(0, 150),
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addDays(5),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER15',
                'name' => 'Khuyến mãi mùa hè',
                'description' => 'Giảm 15% cho tất cả sản phẩm',
                'type' => Coupon::TYPE_PERCENTAGE,
                'value' => 15,
                'min_purchase_amount' => 300000,
                'max_discount_amount' => 1000000,
                'usage_limit' => null,
                'used_count' => $faker->numberBetween(0, 1000),
                'usage_limit_per_user' => 3,
                'starts_at' => Carbon::now()->subDays(20),
                'expires_at' => Carbon::now()->addDays(40),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        // Tạo thêm 10 coupon ngẫu nhiên
        for ($i = 0; $i < 10; $i++) {
            $type = $faker->randomElement([Coupon::TYPE_PERCENTAGE, Coupon::TYPE_FIXED]);
            $value = $type === Coupon::TYPE_PERCENTAGE 
                ? $faker->numberBetween(5, 30) 
                : $faker->numberBetween(50000, 500000);
            
            $startsAt = Carbon::now()->subDays($faker->numberBetween(0, 30));
            $expiresAt = $startsAt->copy()->addDays($faker->numberBetween(30, 90));

            Coupon::create([
                'code' => strtoupper($faker->unique()->bothify('COUPON##??')),
                'name' => $faker->sentence(3),
                'description' => $faker->sentence(10),
                'type' => $type,
                'value' => $value,
                'min_purchase_amount' => $faker->boolean(70) ? $faker->numberBetween(200000, 2000000) : null,
                'max_discount_amount' => $type === Coupon::TYPE_PERCENTAGE && $faker->boolean(60) 
                    ? $faker->numberBetween(100000, 2000000) 
                    : null,
                'usage_limit' => $faker->boolean(60) ? $faker->numberBetween(50, 1000) : null,
                'used_count' => $faker->numberBetween(0, 500),
                'usage_limit_per_user' => $faker->boolean(70) ? $faker->numberBetween(1, 5) : null,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'is_active' => $faker->boolean(80), // 80% active
            ]);
        }
    }
}

