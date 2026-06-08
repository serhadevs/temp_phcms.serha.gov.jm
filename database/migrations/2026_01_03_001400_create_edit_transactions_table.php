<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('edit_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('application_type_id')->nullable();
            $table->integer('table_id')->nullable();
            $table->integer('system_operation_type_id')->nullable();
            $table->integer('edit_type_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('approved')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('edit_transactions');
    }
};
