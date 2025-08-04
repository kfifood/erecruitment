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
        $table->timestamp('security_notification_sent_at')->nullable()->after('invitation_sent_at');
        $table->string('security_notification_status')->nullable()->after('security_notification_sent_at');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interviews', function (Blueprint $table) {
            //
        });
    }
};
