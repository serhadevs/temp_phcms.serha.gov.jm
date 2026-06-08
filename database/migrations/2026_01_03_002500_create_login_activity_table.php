<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_activity', function (Blueprint $table) {
            $table->id();
            $table->dateTime('login_time')->nullable();
            $table->dateTime('logout_time')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('platform')->nullable();
            $table->timestamps();
            $table->string('user_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('ip_address')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_activity');
    }
};
