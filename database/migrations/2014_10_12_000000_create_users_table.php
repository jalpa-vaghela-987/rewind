<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('new_email')->nullable()->default(null);
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('id_proof')->nullable();
            $table->string('account_id')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('street')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('phone_prefix',10)->nullable();
            $table->string('phone')->nullable();
            $table->integer('status')->default(0)->comment('0 = pending, 1 = active, 2 = declined');
            $table->integer('premium_validation_status')->default(0)->comment('0 = pending, 1 = active, 2 = declined');
            $table->string('phone_verification_string',50)->nullable();
            $table->boolean('phone_verified')->default(false)->comment('0-notVerified, 1-verified');
            $table->string('email_verification_string',50)->nullable();
            $table->boolean('email_verified')->default(false)->comment('0-notVerified, 1-verified');
            $table->integer('otp')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->string('persona_account_id')->nullable();
            $table->tinyInteger('registration_step')->default(1)->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            if (Fortify::confirmsTwoFactorAuthentication()) {
                $table->timestamp('two_factor_confirmed_at')->nullable();
            }
            $table->foreignId('current_team_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
};
