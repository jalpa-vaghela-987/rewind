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
        Schema::create('sell_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('units')->nullable()->default(0);
            $table->integer('remaining_units')->default(0);
            $table->double('price_per_unit', 15, 2)->default(0);
            $table->boolean('is_main')->default(false)->comment("To identify this is the main entry of the certificate table or not?");
            $table->integer('status')->default(1)->comment("1=pending, 2=approved, 3=onSell, 4=declined");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sell_certificates');
    }
};
