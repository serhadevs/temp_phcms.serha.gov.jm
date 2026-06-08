<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sign_offs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_granted')->default(0);
            $table->string('permit_no')->nullable();
            $table->string('ecard_id')->nullable();
            $table->string('refusal_reason')->nullable();
            $table->date('sign_off_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('application_type_id')->nullable();
            $table->integer('application_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sign_offs');
    }
};
