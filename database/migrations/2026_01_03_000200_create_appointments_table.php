<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('appointment_date')->nullable();
            $table->integer('facility_id')->nullable();
            $table->integer('permit_application_id')->nullable();
            $table->integer('health_cert_application_id')->nullable();
            $table->integer('exam_date_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('rescheduled')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
