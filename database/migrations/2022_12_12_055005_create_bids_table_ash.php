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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates');
            $table->foreignId('sell_certificate_id')->constrained('sell_certificates');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('card_detail_id')->constrained('card_details');
            $table->double('amount', 15, 2)->nullable()->default(0);
            $table->double('rate', 15, 2)->nullable()->default(0);
            $table->integer('unit')->default(0);
            $table->date('expiration_date');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('bids');
    }
};
