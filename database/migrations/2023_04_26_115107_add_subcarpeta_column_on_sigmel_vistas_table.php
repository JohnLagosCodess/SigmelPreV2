<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sigmel_vistas', function (Blueprint $table) {
            $table->string('subcarpeta', 100)->nullable()->after('carpeta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sigmel_vistas', function (Blueprint $table) {
            $table->dropColumn('subcarpeta');
        });
    }
};
