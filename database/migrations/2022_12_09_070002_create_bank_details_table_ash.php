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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('name')->comment('bank name');
            $table->string('bic');
            $table->string('iban');
            $table->string('beneficiary_name')->nullable()->default(null);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(false)->comment("0-deactivated,1-active");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            //
        });
    }
};
