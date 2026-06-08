<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('application_type_id')->nullable();
            $table->integer('application_id')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('facility_id')->nullable();
            $table->integer('cashier_user_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->decimal('change_amt', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('manual_receipt_no')->nullable();
            $table->timestamp('manual_receipt_date')->nullable();
            $table->integer('payment_type_id')->nullable();
            $table->date('wire_transfer_date')->nullable();
            $table->integer('waiver_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
