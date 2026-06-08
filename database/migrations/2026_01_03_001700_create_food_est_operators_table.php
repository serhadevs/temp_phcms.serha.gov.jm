<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('food_est_operators', function (Blueprint $table) {
            $table->id();
            $table->integer('establishment_application_id')->nullable();
            $table->string('name_of_operator')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('food_est_operators');
    }
};
