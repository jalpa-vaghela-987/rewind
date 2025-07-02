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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_type_id')->constrained('project_types')->cascadeOnDelete();
            $table->unsignedBigInteger('country_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->float('price')->nullable();
            $table->smallInteger('quantity');
            $table->text('approving_body')->nullable();
            $table->text('link_to_certificate')->nullable();
            $table->integer('status')->default(1)->comment("1=pending, 2=approved, 3=onSell, 4=declined");
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
        Schema::dropIfExists('certificates');
    }
};
