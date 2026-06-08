<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zipped_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('application_type_id')->nullable();
            $table->integer('application_id')->nullable();
            $table->integer('download_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('written')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('zipped_applications');
    }
};
