<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('discounts', 'points_used')) {
                $table->integer('points_used')->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('discounts', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['points_used', 'used_at']);
        });
    }
};