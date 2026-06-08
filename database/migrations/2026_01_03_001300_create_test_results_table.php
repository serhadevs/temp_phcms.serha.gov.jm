<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->integer('application_type_id')->nullable();
            $table->integer('application_id')->nullable();
            $table->string('test_location')->nullable();
            $table->string('staff_contact')->nullable();
            $table->date('test_date')->nullable();
            $table->string('comments')->nullable();
            $table->decimal('critical_score', 8, 2)->nullable();
            $table->decimal('overall_score', 8, 2)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->integer('number_employees')->nullable();
            $table->integer('number_emp_permits')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('visit_purpose')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
