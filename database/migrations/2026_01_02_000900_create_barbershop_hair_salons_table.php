<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barbershop_hair_salons', function (Blueprint $table) {
            $table->id();
            $table->string('establishment_name');
            $table->string('est_type')->nullable();
            $table->string('operator')->nullable();
            $table->string('applicant_address')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_description')->nullable();
            $table->string('telephone')->nullable();
            $table->string('alt_telephone')->nullable();
            $table->integer('no_of_employees')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('application_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barbershop_hair_salons');
    }
};
