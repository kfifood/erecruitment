<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->timestamp('result_sent_at')
                  ->nullable()
                  ->after('decision');
        });
    }

    public function down()
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->dropColumn('result_sent_at');
        });
    }
};