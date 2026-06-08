<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->integer('application_type_id')->nullable();
            $table->integer('application_amount')->nullable();
            $table->string('category')->nullable();
            $table->string('download_url')->nullable();
            $table->date('download_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('downloads');
    }
};
