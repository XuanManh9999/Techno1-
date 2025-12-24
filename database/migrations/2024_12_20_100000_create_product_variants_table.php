<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('name')->nullable(); // Tên variant (ví dụ: "iPhone 15 Pro Max 256GB - Xanh Titan")
            $table->json('attributes'); // { "Màu sắc": "Xanh Titan", "Dung lượng": "256GB" }
            $table->decimal('price', 15, 2)->nullable(); // Giá riêng của variant, null thì dùng giá product
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('image')->nullable(); // Ảnh riêng cho variant
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

