<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('distance_km', 10, 2)->nullable()->after('delivery_instructions');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('distance_km');
            $table->decimal('rider_earnings', 10, 2)->default(0)->after('delivery_fee');
            $table->decimal('admin_earnings', 10, 2)->default(0)->after('rider_earnings');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['distance_km', 'delivery_fee', 'rider_earnings', 'admin_earnings']);
        });
    }
};