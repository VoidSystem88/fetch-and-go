<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('pickup_lat', 10, 8)->nullable()->after('pickup_location');
        $table->decimal('pickup_lng', 11, 8)->nullable()->after('pickup_lat');
        $table->decimal('dropoff_lat', 10, 8)->nullable()->after('dropoff_location');
        $table->decimal('dropoff_lng', 11, 8)->nullable()->after('dropoff_lat');
    });
}
};
