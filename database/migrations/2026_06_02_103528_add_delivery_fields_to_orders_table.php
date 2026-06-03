<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'estimated_size_cm')) {
                $table->decimal('estimated_size_cm', 8, 2)->nullable()->after('estimated_weight_kg');
            }
            if (!Schema::hasColumn('orders', 'distance_km')) {
                $table->decimal('distance_km', 8, 2)->nullable()->after('dropoff_location');
            }
            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->nullable()->after('distance_km');
            }
            if (!Schema::hasColumn('orders', 'rider_earnings')) {
                $table->decimal('rider_earnings', 10, 2)->nullable()->after('delivery_fee');
            }
            if (!Schema::hasColumn('orders', 'admin_earnings')) {
                $table->decimal('admin_earnings', 10, 2)->nullable()->after('rider_earnings');
            }
            if (!Schema::hasColumn('orders', 'discount_code')) {
                $table->string('discount_code')->nullable()->after('admin_earnings');
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_code');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'pending', 'paid'])->default('unpaid')->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_size_cm', 'distance_km', 'delivery_fee', 
                'rider_earnings', 'admin_earnings', 'discount_code', 
                'discount_amount', 'payment_status'
            ]);
        });
    }
};