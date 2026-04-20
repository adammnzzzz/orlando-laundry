<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->decimal('discount', 15, 2)->default(0)->after('order_status');
            $table->decimal('tax', 15, 2)->default(0)->after('discount');
            $table->decimal('grand_total', 15, 2)->default(0)->after('tax');
            
            // Mengubah tipe total menjadi decimal untuk akurasi (optional but recommended)
            $table->decimal('total', 15, 2)->change();
            $table->decimal('order_pay', 15, 2)->change();
            $table->decimal('order_change', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax', 'grand_total']);
        });
    }
};
