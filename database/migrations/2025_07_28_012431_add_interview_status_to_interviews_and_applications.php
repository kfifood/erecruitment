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
    Schema::table('interviews', function (Blueprint $table) {
        $table->enum('interview_status', ['not yet', 'interviewed'])
              ->default('not yet')
              ->after('security_notification_status');
    });

    Schema::table('applications', function (Blueprint $table) {
        $table->enum('interview_status', ['not yet', 'interviewed'])
              ->nullable()
              ->after('status');
    });
}

public function down()
{
    Schema::table('interviews', function (Blueprint $table) {
        $table->dropColumn('interview_status');
    });

    Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn('interview_status');
    });
}
};
