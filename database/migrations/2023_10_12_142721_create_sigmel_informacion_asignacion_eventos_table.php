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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_asignacion_eventos', function (Blueprint $table) {
            $table->increments('Id_Asignacion');
            $table->string('ID_evento', 15);
            $table->integer('Id_proceso');
            $table->enum('Visible_Nuevo_Proceso', ['Si','No'])->default('Si')->nullable();
            $table->integer('Id_servicio');
            $table->enum('Visible_Nuevo_Servicio', ['Si','No'])->default('Si')->nullable();
            $table->integer('Id_accion');
            $table->text('Descripcion')->nullable();
            $table->date('F_alerta')->nullable();
            $table->integer('Id_Estado_evento')->nullable();
            $table->date('F_accion')->nullable();
            $table->date('F_radicacion')->nullable();
            $table->string('N_de_orden', 20)->nullable();
            $table->integer('Id_proceso_anterior')->nullable();
            $table->integer('Id_servicio_anterior')->nullable();
            $table->dateTime('F_asignacion_calificacion')->nullable();
            $table->text('Consecutivo_dictamen')->nullable();
            $table->integer('Id_profesional')->nullable();
            $table->text('Nombre_profesional')->nullable();
            $table->text('Descripcion_bandeja')->nullable();            
            $table->date('F_calificacion')->nullable();           
            $table->date('F_ajuste_calificacion')->nullable();            
            $table->enum('Detener_tiempo_gestion', ['Si','No'])->nullable();
            $table->date('F_detencion_tiempo_gestion')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_asignacion_eventos');
    }
};