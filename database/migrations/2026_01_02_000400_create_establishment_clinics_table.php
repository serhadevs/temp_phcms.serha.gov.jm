<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('establishment_clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->integer('no_of_employees')->nullable();
            $table->date('proposed_date')->nullable();
            $table->string('proposed_time')->nullable();
            $table->date('application_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('due_payments')->nullable();
            $table->integer('submitted_by_id')->nullable();
            $table->integer('waiver_establishment_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('establishment_clinics');
    }
};
