<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waiver_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('waiver_id')->nullable();
            $table->integer('approved_by')->nullable();
            $table->enum('approval_status', ['approved', 'rejected'])->nullable();
            $table->integer('establishment_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waiver_approvals');
    }
};
