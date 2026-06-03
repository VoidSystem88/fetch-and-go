<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 
                'sent', 
                'accepted', 
                'declined', 
                'approved', 
                'assigned', 
                'picked_up', 
                'delivered', 
                'cancelled'
            ])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 
                'approved', 
                'assigned', 
                'picked_up', 
                'delivered', 
                'cancelled'
            ])->default('pending')->change();
        });
    }
};