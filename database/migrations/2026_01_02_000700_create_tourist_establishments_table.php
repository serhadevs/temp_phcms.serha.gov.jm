<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tourist_establishments', function (Blueprint $table) {
            $table->id();
            $table->string('establishment_name');
            $table->string('establishment_address')->nullable();
            $table->integer('bed_capacity')->nullable();
            $table->string('permit_no')->nullable();
            $table->tinyInteger('is_eating_establishment')->default(0);
            $table->mediumText('eating_establishment_description')->nullable();
            $table->enum('establishment_state', ['new', 'now being operated'])->nullable();
            $table->longText('authorized_officer_statement')->nullable();
            $table->string('officer_firstname')->nullable();
            $table->string('officer_lastname')->nullable();
            $table->date('statement_date')->nullable();
            $table->tinyInteger('sign_off_status')->nullable();
            $table->date('application_date')->nullable();
            $table->tinyInteger('reprint')->default(0);
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('submitted_by_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tourist_establishments');
    }
};
