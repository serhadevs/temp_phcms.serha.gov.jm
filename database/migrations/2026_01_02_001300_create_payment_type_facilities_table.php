<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_type_facilities', function (Blueprint $table) {
            $table->integer('payment_type_id');
            $table->integer('facility_id');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['payment_type_id', 'facility_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_type_facilities');
    }
};
