<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ecard_access_history', function (Blueprint $table) {
            $table->id();
            $table->integer('sign_off_id')->nullable();
            $table->string('ecard_id')->nullable();
            $table->string('access_type')->nullable();
            $table->string('access_method')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('accessed_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecard_access_history');
    }
};
