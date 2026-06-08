<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_users', function (Blueprint $table) {
            if (!Schema::hasColumn('online_users', 'email')) {
                $table->string('email')->nullable()->after('permit_no');
            }
            if (!Schema::hasColumn('online_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_users', function (Blueprint $table) {});
    }
};
