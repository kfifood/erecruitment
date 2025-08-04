<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->enum('decision', ['hired', 'unhired'])
                  ->nullable()
                  ->after('notes')
                  ->comment('Keputusan akhir setelah penilaian');
        });
    }

    public function down()
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->dropColumn('decision');
        });
    }
};