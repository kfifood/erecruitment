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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->constrained('employees')->onDelete('cascade');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->enum('method', ['offsite', 'online']);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['interview_date', 'interviewer_id']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interviews');
    }
};
