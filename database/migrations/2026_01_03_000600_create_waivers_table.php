<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waivers', function (Blueprint $table) {
            $table->id();
            $table->integer('waiver_establishment_id')->nullable();
            $table->integer('application_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('waiver_reason')->nullable();
            $table->integer('application_type_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waivers');
    }
};
