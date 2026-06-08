<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('travel_history', function (Blueprint $table) {
            $table->id();
            $table->integer('permit_application_id')->nullable();
            $table->integer('health_cert_application_id')->nullable();
            $table->string('destination')->nullable();
            $table->string('travel_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_history');
    }
};
