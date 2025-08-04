<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
   Schema::table('users', function (Blueprint $table) {
        $table->enum('division', [
            'management',
            'finance_accounting',
            'human_resources',
            'information_technology',
            'quality_assurance',
            'marketing',
            'technic',
            'ppic',
            'production'
        ])->nullable()->after('role'); // after() opsional untuk menentukan posisi kolom
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('division');
    });
}
};
