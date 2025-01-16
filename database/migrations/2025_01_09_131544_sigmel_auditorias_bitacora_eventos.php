<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigmelAuditoriasBitacoraEventosTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigmel_auditorias_bitacora_eventos', function (Blueprint $table) {
            $table->id('id_bitacora'); // id_bitacora como auto-incremento
            $table->integer('Id_accion')->nullable(); // Id_accion
            $table->integer('Id_Asignacion')->nullable(); // Id_Asignacion
            $table->string('ID_evento', 20)->nullable(); // ID_evento como varchar(20)
            $table->integer('Id_proceso')->nullable(); // Id_proceso
            $table->integer('Id_servicio')->nullable(); // Id_servicio
            $table->text('Descripcion')->nullable(); // Descripcion como texto
            $table->dateTime('F_accion')->nullable(); // F_accion como datetime
            $table->text('Nombre_usuario')->nullable(); // Nombre_usuario como texto
            $table->primary('id_bitacora'); // Definir id_bitacora como clave primaria
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sigmel_auditorias_bitacora_eventos');
    }
}
