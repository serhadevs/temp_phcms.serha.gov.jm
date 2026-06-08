<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_key')->unique();
            $table->string('product_name');
            $table->string('client_name');
            $table->string('client_email');
            $table->integer('max_activations')->default(1);
            $table->integer('current_activations')->default(0);
            $table->enum('status', ['active', 'expired', 'revoked', 'suspended'])->default('active');
            $table->timestamp('issued_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('last_verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_licenses');
    }
};
