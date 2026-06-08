<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permit_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('permit_category_id')->nullable();
            $table->integer('establishment_clinic_id')->nullable();
            $table->integer('appointment_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('permit_no')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['female', 'male'])->nullable();
            $table->enum('permit_type', ['regular', 'student', 'teacher'])->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('email')->nullable();
            $table->string('trn')->nullable();
            $table->tinyInteger('applied_before')->default(0);
            $table->tinyInteger('granted')->default(0);
            $table->text('reason')->nullable();
            $table->string('photo_upload')->nullable();
            $table->tinyInteger('sign_off_status')->nullable();
            $table->tinyInteger('reprint')->default(0);
            $table->date('application_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('no_of_years')->nullable();
            $table->string('signature_link')->nullable();
            $table->integer('submitted_by_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permit_applications');
    }
};
