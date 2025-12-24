<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->onDelete('cascade');
            $table->dropUnique(['user_id', 'product_id']);
            $table->unique(['user_id', 'product_id', 'variant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropUnique(['user_id', 'product_id', 'variant_id']);
            $table->dropColumn('variant_id');
            $table->unique(['user_id', 'product_id']);
        });
    }
};

