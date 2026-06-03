<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('riders', function (Blueprint $table) {
        $table->decimal('total_earnings', 10, 2)->default(0)->after('total_deliveries');
    });
}
};
