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
        Schema::create('jobs', function (Blueprint $table) {
    $table->id();
    $table->string('position');
    $table->text('description');
    $table->text('qualification');
    $table->foreignId('division_id')->nullable()->constrained()->onDelete('set null');
    $table->string('location');
    $table->boolean('is_active')->default(true);
      $table->date('posted_date')->nullable();
    $table->date('closing_date')->nullable();
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
        Schema::dropIfExists('jobs');
    }
};
