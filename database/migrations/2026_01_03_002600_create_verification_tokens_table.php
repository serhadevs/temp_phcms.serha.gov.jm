<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->integer('permit_application_id')->nullable();
            $table->string('token_hash')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->tinyInteger('used')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_tokens');
    }
};
