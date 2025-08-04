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
    Schema::table('interview_scores', function (Blueprint $table) {
        // Hapus kolom id yang ada
        $table->dropColumn('id');
        
        // Tambahkan kolom id baru dengan auto-increment
        $table->id()->first();
    });
}

public function down()
{
    Schema::table('interview_scores', function (Blueprint $table) {
        $table->dropColumn('id');
        $table->unsignedBigInteger('id')->first();
    });
}
};
