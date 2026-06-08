<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('collected_cards', function (Blueprint $table) {
            $table->string('identification_number')->nullable()->after('application_type');
            $table->integer('identification_type_id')->nullable()->after('identification_number');
            $table->date('issue_date')->nullable()->after('collected_by');
            $table->date('expiry_date')->nullable()->after('issue_date');
            $table->integer('user_id')->nullable()->after('expiry_date');
            $table->softDeletes();
            $table->integer('pick_up_id')->nullable();
            $table->string('bearer_firstname')->nullable();
            $table->string('bearer_lastname')->nullable();
            $table->string('bearer_contact_number')->nullable();
            $table->integer('terms')->default(0);
        });
    }

    public function down()
    {
        Schema::table('collected_cards', function (Blueprint $table) {
            $table->dropColumn([
                'identification_number', 'identification_type_id', 'issue_date', 'expiry_date',
                'user_id', 'deleted_at', 'pick_up_id', 'bearer_firstname', 'bearer_lastname',
                'bearer_contact_number', 'terms',
            ]);
        });
    }
};
