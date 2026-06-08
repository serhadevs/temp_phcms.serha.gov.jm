<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('establishment_applications', function (Blueprint $table) {
            $table->dropColumn(['zone', 'prev_est_closed', 'current_est_closed', 'sign_off_status', 'reprint']);
        });

        Schema::table('establishment_applications', function (Blueprint $table) {
            $table->string('zone')->nullable()->after('trn');
            $table->tinyInteger('prev_est_closed')->default(0)->after('establishment_category_id');
            $table->tinyInteger('current_est_closed')->default(0)->after('prev_est_closed');
            $table->tinyInteger('sign_off_status')->nullable()->after('closure_date');
            $table->tinyInteger('reprint')->default(0)->after('sign_off_status');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('submitted_by_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('establishment_applications', function (Blueprint $table) {
            $table->dropColumn([
                'zone', 'prev_est_closed', 'current_est_closed', 'sign_off_status', 'reprint',
                'created_at', 'updated_at', 'deleted_at', 'submitted_by_id',
            ]);
        });

        Schema::table('establishment_applications', function (Blueprint $table) {
            $table->integer('zone')->nullable()->after('trn');
            $table->boolean('prev_est_closed')->after('establishment_category_id');
            $table->boolean('current_est_closed')->after('prev_est_closed');
            $table->boolean('sign_off_status')->nullable()->after('closure_date');
            $table->boolean('reprint')->nullable()->after('sign_off_status');
        });
    }
};
