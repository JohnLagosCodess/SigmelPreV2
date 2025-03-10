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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_acciones', function (Blueprint $table) {
            $table->increments('Id_Accion');
            $table->integer('Estado_accion');
            $table->text('Accion');
            $table->text('Descripcion_accion');
            $table->enum('Status_accion', ['Activo', 'Inactivo']);
            $table->date('F_creacion_accion');
            $table->text('Nombre_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_acciones');
    }
};
