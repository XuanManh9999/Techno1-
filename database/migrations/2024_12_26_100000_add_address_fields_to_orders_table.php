<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('province_id')->nullable()->after('shipping_address');
            $table->integer('district_id')->nullable()->after('province_id');
            $table->integer('ward_id')->nullable()->after('district_id');
            $table->string('address_detail')->nullable()->after('ward_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'district_id', 'ward_id', 'address_detail']);
        });
    }
};

