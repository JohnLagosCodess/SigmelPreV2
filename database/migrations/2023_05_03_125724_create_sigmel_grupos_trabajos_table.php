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
 
        Schema::connection('sigmel_gestiones')->create('sigmel_grupos_trabajos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('Id_proceso_equipo');
            $table->text('nombre');
            $table->unsignedInteger('lider');
            $table->integer('Accion')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_grupos_trabajos');
    }
};
