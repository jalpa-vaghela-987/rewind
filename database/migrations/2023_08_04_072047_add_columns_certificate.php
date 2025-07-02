<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->integer('project_year')->nullable()->after('status');
            $table->double('lattitude', 10, 8)->nullable()->after('project_year');
            $table->double('longitude', 11, 8)->nullable()->after('lattitude');
            $table->integer('vintage')->nullable()->after('longitude');
            $table->string('verify_by')->nullable()->after('vintage');
            $table->double('total_size', 8, 2)->nullable()->after('verify_by');
            $table->string('registry_id')->nullable()->after('total_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['project_year', 'lattitude', 'longitude', 'vintage', 'verify_by', 'total_size', 'registry_id']);
        });
    }
};
