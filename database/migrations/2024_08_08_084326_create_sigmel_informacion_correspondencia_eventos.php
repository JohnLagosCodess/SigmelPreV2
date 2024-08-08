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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_correspondencia_eventos', function (Blueprint $table) {
            $table->increments('Id_Correspondencia');
            $table->string('ID_evento', 20);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->integer('Id_servicio');
            $table->integer('Id_comunicado');
            $table->text('Nombre_afiliado');
            $table->string('N_identificacion', 25);
            $table->text('N_radicado');
            $table->text('N_orden');
            $table->text('Tipo_destinatario');
            $table->text('Nombre_destinatario');
            $table->text('Direccion_destinatario');
            $table->text('Departamento');
            $table->text('Ciudad');
            $table->text('Telefono_destinatario');
            $table->text('Email_destinatario');
            $table->text('Medio_notificacion');
            $table->text('N_guia')->nullable();
            $table->integer('Folios')->nullable();
            $table->date('F_envio')->nullable();;
            $table->date('F_notificacion')->nullable();;
            $table->integer('Id_Estado_corresp');
            $table->text('Tipo_correspondencia');
            $table->string('Nombre_usuario', 100);
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_correspondencia_eventos');
    }
};
