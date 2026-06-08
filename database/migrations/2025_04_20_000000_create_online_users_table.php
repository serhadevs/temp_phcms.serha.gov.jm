<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('online_users', function (Blueprint $table) {
            $table->id();
            $table->string('permit_no')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->string('activation_token')->nullable();
            $table->timestamp('activation_expires_at')->nullable();
            $table->string('activation_code')->nullable();
            $table->timestamp('activated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('online_users');
    }
};
