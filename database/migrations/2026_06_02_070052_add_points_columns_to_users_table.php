<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'points')) {
                $table->integer('points')->default(0)->after('phone');
            }
            if (!Schema::hasColumn('users', 'total_points_earned')) {
                $table->integer('total_points_earned')->default(0)->after('points');
            }
            if (!Schema::hasColumn('users', 'points_spent')) {
                $table->integer('points_spent')->default(0)->after('total_points_earned');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'total_points_earned', 'points_spent']);
        });
    }
};