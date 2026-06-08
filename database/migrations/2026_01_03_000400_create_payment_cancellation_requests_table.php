<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->nullable();
            $table->string('reason')->nullable();
            $table->integer('approved')->nullable();
            $table->integer('requester_user_id')->nullable();
            $table->integer('approver_user_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_cancellation_requests');
    }
};
