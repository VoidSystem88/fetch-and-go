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
        Schema::table('orders', function (Blueprint $table) {
        $table->timestamp('sent_to_rider_at')->nullable();
        $table->timestamp('rider_responded_at')->nullable();
        $table->text('rider_decline_reason')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
};
