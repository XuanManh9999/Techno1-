<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'variant_id')) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->onDelete('cascade');
            }
        });

        // Drop old unique constraint if exists
        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'product_id']);
            });
        } catch (\Exception $e) {
            // Unique constraint doesn't exist, ignore
        }

        // Add new unique constraint
        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique(['user_id', 'product_id', 'variant_id'], 'carts_user_product_variant_unique');
            });
        } catch (\Exception $e) {
            // Unique constraint already exists, ignore
        }
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

