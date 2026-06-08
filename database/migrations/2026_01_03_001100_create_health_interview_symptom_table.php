<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_interview_symptom', function (Blueprint $table) {
            $table->id();
            $table->integer('health_interview_id')->nullable();
            $table->integer('symptom_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_interview_symptom');
    }
};
