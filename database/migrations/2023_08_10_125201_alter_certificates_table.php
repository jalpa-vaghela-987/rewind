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
        //checking before moving image of certificate available in multiple storing file table or not
        if ( Schema::hasColumn('certificates', 'file_path') ) {

            \App\Models\Certificate::query()->whereNotNull('file_path')->get()->map(function ($item) {
                if ( !blank($item->file_path) ) {
                    $isExist = \App\Models\CertificateFile::query()->where('certificate_id', $item->id)->where('file_path', $item->file_path)->count();
                    if ( !$isExist ) {
                        $item->files()->create([
                            'file_path' => $item->file_path,
                        ]);
                    }
                }
            });

            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ( !Schema::hasColumn('certificates', 'file_path') ) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->string('file_path')->after('description')->nullable();
            });

            \App\Models\Certificate::query()->get()->map(function ($item) {
                if ( $item->files()->exists() ) {
                    $file_path = $item->files()->first()->file_path;
                    $item->update([
                        'file_path' => $file_path,
                    ]);
                }
            });
        }
    }
};
