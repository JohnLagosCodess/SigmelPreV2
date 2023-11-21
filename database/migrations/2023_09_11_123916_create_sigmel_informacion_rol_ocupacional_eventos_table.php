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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_rol_ocupacional_eventos', function (Blueprint $table) {
            $table->increments('Id_Rol_ocupacional');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->text('Poblacion_calificar')->nullable();
            $table->string('Motriz_postura_simetrica', 4)->nullable();
            $table->string('Motriz_actividad_espontanea', 4)->nullable();
            $table->string('Motriz_sujeta_cabeza', 4)->nullable();
            $table->string('Motriz_sentarse_apoyo', 4)->nullable();
            $table->string('Motriz_gira_sobre_mismo', 4)->nullable();
            $table->string('Motriz_sentanser_sin_apoyo', 4)->nullable();
            $table->string('Motriz_pasa_tumbado_sentado', 4)->nullable();
            $table->string('Motriz_pararse_apoyo', 4)->nullable();
            $table->string('Motriz_pasos_apoyo', 4)->nullable();
            $table->string('Motriz_pararse_sin_apoyo', 4)->nullable();
            $table->string('Motriz_anda_solo', 4)->nullable();
            $table->string('Motriz_empujar_pelota_pies', 4)->nullable();
            $table->string('Motriz_andar_obstaculos', 4)->nullable();
            $table->string('Adaptativa_succiona', 4)->nullable();
            $table->string('Adaptativa_fija_mirada', 4)->nullable();
            $table->string('Adaptativa_sigue_trayectoria_objeto', 4)->nullable();
            $table->string('Adaptativa_sostiene_sonajero', 4)->nullable();
            $table->string('Adaptativa_tiende_mano_hacia_objeto', 4)->nullable();
            $table->string('Adaptativa_sostiene_objeto_manos', 4)->nullable();
            $table->string('Adaptativa_abre_cajones', 4)->nullable();
            $table->string('Adaptativa_bebe_solo', 4)->nullable();
            $table->string('Adaptativa_quitar_prenda_vestir', 4)->nullable();
            $table->string('Adaptativa_reconoce_funcion_espacios_casa', 4)->nullable();
            $table->string('Adaptativa_imita_trazo_lapiz', 4)->nullable();
            $table->string('Adaptativa_abre_puerta', 4)->nullable();
            $table->string('Total_criterios_desarrollo', 4)->nullable();
            $table->string('Juego_estudio_clase', 4)->nullable();
            $table->string('Total_rol_estudio_clase', 4)->nullable();
            $table->string('Adultos_mayores', 4)->nullable();
            $table->string('Total_rol_adultos_ayores', 4)->nullable();
            $table->enum('Estado',['Activo', 'Inactivo'])->default('Activo')->nullable();
            $table->enum('Estado_Recalificacion',['Activo', 'Inactivo'])->default('Activo')->nullable(); 
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_rol_ocupacional_eventos');
    }
};
