<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OtpSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('otp_uri')->nullable();
        });

        Schema::create('otp_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('otpable');
            $table->string('token', 64)->unique();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_uri');
        });

        Schema::drop('otp_sessions');
    }
}
