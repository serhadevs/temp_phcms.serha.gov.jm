<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_cert_applications', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('address')->nullable();
            $table->string('permit_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('sex', ['female', 'male'])->nullable();
            $table->string('telephone')->nullable();
            $table->string('trn')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->string('employer_address')->nullable();
            $table->tinyInteger('applied_before')->default(0);
            $table->tinyInteger('granted')->default(0);
            $table->text('reason')->nullable();
            $table->tinyInteger('sign_off_status')->nullable();
            $table->tinyInteger('reprint')->default(0);
            $table->date('application_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('submitted_by_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_cert_applications');
    }
};
