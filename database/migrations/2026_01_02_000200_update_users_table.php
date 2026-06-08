<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->nullable()->after('id');
            $table->string('firstname')->nullable()->after('role_id');
            $table->string('lastname')->nullable()->after('firstname');
            $table->integer('facility_id')->nullable()->after('lastname');
            $table->string('telephone')->nullable()->after('facility_id');
            $table->integer('status')->default(1)->after('remember_token');
            $table->timestamp('last_seen')->nullable()->after('status');
            $table->integer('default_filter_id')->nullable()->after('last_seen');
            $table->timestamp('password_changed_at')->nullable()->after('default_filter_id');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role_id', 'firstname', 'lastname', 'facility_id', 'telephone',
                'status', 'last_seen', 'default_filter_id', 'password_changed_at', 'deleted_at',
            ]);
        });
    }
};
