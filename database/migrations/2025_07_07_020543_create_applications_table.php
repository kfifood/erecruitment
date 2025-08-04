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
        Schema::create('applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('job_id')->constrained()->onDelete('cascade');
    $table->string('full_name');
    $table->string('email');
    $table->string('phone');
    $table->text('address');
    $table->string('education');
    $table->date('birth_date');
    $table->string('cv');
    $table->string('cover_letter');
    $table->enum('status', ['submitted', 'interview', 'rejected'])->default('submitted');
    $table->timestamp('submitted_at')->useCurrent();
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
        Schema::dropIfExists('applications');
    }
};
