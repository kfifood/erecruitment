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
    // Tambahkan kolom experience
    Schema::table('jobs', function (Blueprint $table) {
        $table->integer('experience')->nullable()->after('qualification');
    });

    // Buat tabel job_educations
    Schema::create('job_educations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('job_id')->constrained()->onDelete('cascade');
        $table->enum('level', ['SMP', 'SMA', 'SMK', 'D3', 'D4', 'S1', 'S2']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};
