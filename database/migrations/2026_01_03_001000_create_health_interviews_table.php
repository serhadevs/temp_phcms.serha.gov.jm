<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_interviews', function (Blueprint $table) {
            $table->id();
            $table->integer('permit_application_id')->nullable();
            $table->integer('health_cert_application_id')->nullable();
            $table->tinyInteger('literate')->default(0);
            $table->tinyInteger('typhoid')->default(0);
            $table->tinyInteger('lived_abroad')->default(0);
            $table->string('lived_abroad_location')->nullable();
            $table->string('lived_abroad_date')->nullable();
            $table->tinyInteger('travel_abroad')->default(0);
            $table->string('whitlow')->nullable();
            $table->string('hands_condition')->nullable();
            $table->string('fingernails_condition')->nullable();
            $table->string('teeth_condition')->nullable();
            $table->string('tests_recommended')->nullable();
            $table->string('tests_results')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_address')->nullable();
            $table->string('doctor_tele')->nullable();
            $table->tinyInteger('sign_off_status')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_interviews');
    }
};
