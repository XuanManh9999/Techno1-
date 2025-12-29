<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Bắt đầu seeding dữ liệu...');
        
        $this->command->info('1. Seeding Users...');
        $this->call(UserSeeder::class);
        
        $this->command->info('2. Seeding Categories...');
        $this->call(CategorySeeder::class);
        
        $this->command->info('3. Seeding Brands...');
        $this->call(BrandSeeder::class);
        
        $this->command->info('4. Seeding Products...');
        $this->call(ProductSeeder::class);
        
        $this->command->info('5. Seeding Carts...');
        $this->call(CartSeeder::class);
        
        $this->command->info('6. Seeding Orders...');
        $this->call(OrderSeeder::class);
        
        $this->command->info('7. Seeding Payments...');
        $this->call(PaymentSeeder::class);
        
        $this->command->info('8. Seeding Coupons...');
        $this->call(CouponSeeder::class);
        
        $this->command->info('9. Seeding Posts...');
        $this->call(PostSeeder::class);
        
        $this->command->info('✅ Hoàn thành seeding dữ liệu!');
        $this->command->info('');
        $this->command->info('Thông tin đăng nhập:');
        $this->command->info('Admin: admin@techno1.com / password');
        $this->command->info('Customer: customer@example.com / password');
    }
}
