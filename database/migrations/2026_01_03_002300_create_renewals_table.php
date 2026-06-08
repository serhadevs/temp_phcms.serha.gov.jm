<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->integer('new_application_id')->nullable();
            $table->integer('application_type_id')->nullable();
            $table->integer('old_application_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('renewals');
    }
};
