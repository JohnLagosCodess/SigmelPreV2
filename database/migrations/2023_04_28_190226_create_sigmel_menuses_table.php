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
        Schema::create('sigmel_menuses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nombre');
            $table->unsignedInteger('id_padre')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->enum('tipo', ['primario', 'secundario']);
            $table->unsignedInteger('rol_id');
            $table->unsignedInteger('vista_id');
            $table->text('icono')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_menuses');
    }
};
