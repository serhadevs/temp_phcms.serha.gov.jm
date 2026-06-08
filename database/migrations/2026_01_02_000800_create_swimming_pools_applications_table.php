<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('swimming_pools_applications', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('permit_no')->nullable();
            $table->string('swimming_pool_address')->nullable();
            $table->date('application_date')->nullable();
            $table->tinyInteger('sign_off_status')->nullable();
            $table->tinyInteger('reprint')->default(0);
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('submitted_by_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('swimming_pools_applications');
    }
};
