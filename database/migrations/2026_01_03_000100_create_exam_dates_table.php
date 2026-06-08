<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('facility_id')->nullable();
            $table->integer('permit_category_id')->nullable();
            $table->integer('application_type_id')->nullable();
            $table->string('exam_day')->nullable();
            $table->string('exam_start_time')->nullable();
            $table->integer('exam_site_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('capacity')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_dates');
    }
};
