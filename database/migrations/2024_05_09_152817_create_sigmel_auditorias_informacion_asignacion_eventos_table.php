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
        Schema::connection('sigmel_auditorias')->create('sigmel_auditorias_informacion_asignacion_eventos', function (Blueprint $table) {
            $table->increments('Id_Aud_Asignacion');
            $table->integer('Aud_Id_Asignacion');
            $table->string('Aud_ID_evento', 15);
            $table->integer('Aud_Id_proceso');
            $table->enum('Aud_Visible_Nuevo_Proceso', ['Si','No'])->default('Si')->nullable();
            $table->integer('Aud_Id_servicio');
            $table->enum('Aud_Visible_Nuevo_Servicio', ['Si','No'])->default('Si')->nullable();
            $table->integer('Aud_Id_accion');
            $table->text('Aud_Descripcion')->nullable();
            $table->date('Aud_F_alerta')->nullable();
            $table->integer('Aud_Id_Estado_evento')->nullable();
            $table->date('Aud_F_accion')->nullable();
            $table->date('Aud_F_radicacion')->nullable();
            $table->string('Aud_N_de_orden', 20)->nullable();
            $table->integer('Aud_Id_proceso_anterior')->nullable();
            $table->integer('Aud_Id_servicio_anterior')->nullable();
            $table->dateTime('Aud_F_asignacion_calificacion')->nullable();
            $table->text('Aud_Consecutivo_dictamen')->nullable();
            $table->integer('Aud_Id_profesional')->nullable();
            $table->text('Aud_Nombre_profesional')->nullable();
            $table->text('Aud_Descripcion_bandeja')->nullable();            
            $table->date('Aud_F_calificacion')->nullable();           
            $table->date('Aud_F_ajuste_calificacion')->nullable();            
            $table->enum('Aud_Detener_tiempo_gestion', ['Si','No'])->nullable();
            $table->date('Aud_F_detencion_tiempo_gestion')->nullable();
            $table->text('Aud_Nombre_usuario');
            $table->date('Aud_F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_auditorias_informacion_asignacion_eventos');
    }
};
