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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('consider this user as sender');
            $table->foreignId('receiver_id');
            $table->foreignId('certificate_id')->constrained('certificates');
            $table->foreignId('sell_certificate_id')->constrained('sell_certificates');
            $table->foreignId('card_detail_id')->constrained('card_details');
            $table->string('name');
            $table->string('ip_address')->nullable();
            $table->unsignedBigInteger('seller_bank_id')->nullable();
            $table->string('stripe_id')->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->float('amount', 8, 2)->default(0);
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'stripe_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
