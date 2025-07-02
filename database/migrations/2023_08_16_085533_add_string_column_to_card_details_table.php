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
        Schema::table('card_details', function (Blueprint $table) {
            $table->string('expiry_month')->change();
            $table->string('cvv')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_details', function (Blueprint $table) {
            $table->tinyInteger('expiry_month')->change();
            $table->smallInteger('cvv')->change();
        });
    }
};
