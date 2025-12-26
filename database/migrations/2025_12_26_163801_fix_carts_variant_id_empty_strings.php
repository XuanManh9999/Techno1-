<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sửa các bản ghi có variant_id là chuỗi rỗng hoặc 0 thành NULL
        // Sử dụng raw query để tránh lỗi type mismatch
        DB::statement("UPDATE carts SET variant_id = NULL WHERE CAST(variant_id AS CHAR) = '' OR variant_id = 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là sửa dữ liệu, không phải thay đổi schema
    }
};
