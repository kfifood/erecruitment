<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewScoresTable extends Migration
{
    public function up()
    {
        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            
            // Aspek Penilaian (Skala 1-9)
            $table->tinyInteger('appearance')->unsigned()->comment('Penampilan (1-9)');
            $table->tinyInteger('experience')->unsigned()->comment('Pengalaman Kerja (1-9)');
            $table->tinyInteger('work_motivation')->unsigned()->comment('Kemauan Kerja (1-9)');
            $table->tinyInteger('problem_solving')->unsigned()->comment('Problem Solving (1-9)');
            $table->tinyInteger('leadership')->unsigned()->comment('Leadership (1-9)');
            $table->tinyInteger('communication')->unsigned()->comment('Komunikasi (1-9)');
            $table->tinyInteger('job_knowledge')->unsigned()->comment('Pengetahuan Pekerjaan (1-9)');
            
            // Hasil Perhitungan
            $table->tinyInteger('final_score')->virtualAs(
                'appearance + experience + work_motivation + problem_solving + leadership + communication + job_knowledge'
            )->comment('Total Skor (7-63)');
            
            $table->string('final_category', 20)->virtualAs(
                "CASE 
                    WHEN (appearance + experience + work_motivation + problem_solving + leadership + communication + job_knowledge) BETWEEN 7 AND 21 THEN 'Tidak Disarankan'
                    WHEN (appearance + experience + work_motivation + problem_solving + leadership + communication + job_knowledge) BETWEEN 22 AND 42 THEN 'Cukup Disarankan'
                    ELSE 'Disarankan'
                END"
            )->comment('Kategori Hasil');
            
            $table->text('notes')->nullable()->comment('Kesimpulan Interviewer');
            $table->timestamps();

            // Foreign Key
            $table->foreign('interview_id')
                  ->references('id')
                  ->on('interviews')
                  ->onDelete('cascade');
            
            // Unique Constraint (1 interview hanya 1 penilaian)
            $table->unique('interview_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_scores');
    }
}