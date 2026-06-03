<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->text('item_description');
            $table->decimal('estimated_weight_kg', 8, 2)->nullable();
            $table->enum('required_vehicle_type', ['motor', 'car', 'truck', 'van', 'L300']);
            $table->enum('status', ['pending', 'approved', 'assigned', 'picked_up', 'delivered', 'cancelled'])->default('pending');
            
            $table->foreignId('assigned_rider_id')->nullable()->constrained('riders');
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('timeout_expires_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};