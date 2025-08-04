<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->text('address')->nullable()->after('location');
            $table->text('full_address')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};