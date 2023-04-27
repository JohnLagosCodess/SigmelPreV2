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
        Schema::table('sigmel_roles', function (Blueprint $table) {
            $table->text('descripcion_rol')->nullable()->after('nombre_rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sigmel_roles', function (Blueprint $table) {
            $table->dropColumn('descripcion_rol');
        });
    }
};
