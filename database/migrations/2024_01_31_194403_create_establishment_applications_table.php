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
        Schema::create('establishment_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('establishment_name');
            $table->string('establishment_address');
            $table->string('permit_no');
            $table->string('food_type');
            $table->string('telephone')->nullable();
            $table->string('alt_telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('trn')->nullable();
            $table->integer('zone')->nullable();
            $table->integer('establishment_category_id')->references('id')->on('establishment_category');
            $table->boolean('prev_est_closed');
            $table->boolean('current_est_closed');
            $table->date('closure_date')->nullable();
            $table->boolean('sign_off_status')->nullable();
            $table->boolean('reprint')->nullable();
            $table->date('application_date');
            $table->integer('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establishment_applications');
    }
};
