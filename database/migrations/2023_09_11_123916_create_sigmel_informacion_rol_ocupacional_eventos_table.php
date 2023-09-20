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
            $table->float('Motriz_postura_simetrica')->nullable();
            $table->float('Motriz_actividad_espontanea')->nullable();
            $table->float('Motriz_sujeta_cabeza')->nullable();
            $table->float('Motriz_sentarse_apoyo')->nullable();
            $table->float('Motriz_gira_sobre_mismo')->nullable();
            $table->float('Motriz_sentanser_sin_apoyo')->nullable();
            $table->float('Motriz_pasa_tumbado_sentado')->nullable();
            $table->float('Motriz_pararse_apoyo')->nullable();
            $table->float('Motriz_pasos_apoyo')->nullable();
            $table->float('Motriz_pararse_sin_apoyo')->nullable();
            $table->float('Motriz_anda_solo')->nullable();
            $table->float('Motriz_empujar_pelota_pies')->nullable();
            $table->float('Motriz_andar_obstaculos')->nullable();
            $table->float('Adaptativa_succiona')->nullable();
            $table->float('Adaptativa_fija_mirada')->nullable();
            $table->float('Adaptativa_sigue_trayectoria_objeto')->nullable();
            $table->float('Adaptativa_sostiene_sonajero')->nullable();
            $table->float('Adaptativa_tiende_mano_hacia_objeto')->nullable();
            $table->float('Adaptativa_sostiene_objeto_manos')->nullable();
            $table->float('Adaptativa_abre_cajones')->nullable();
            $table->float('Adaptativa_bebe_solo')->nullable();
            $table->float('Adaptativa_quitar_prenda_vestir')->nullable();
            $table->float('Adaptativa_reconoce_funcion_espacios_casa')->nullable();
            $table->float('Adaptativa_imita_trazo_lapiz')->nullable();
            $table->float('Adaptativa_abre_puerta')->nullable();
            $table->float('Total_criterios_desarrollo')->nullable();
            $table->string('Juego_estudio_clase', 4)->nullable();
            $table->float('Total_rol_estudio_clase')->nullable();
            $table->string('Adultos_mayores', 4)->nullable();
            $table->float('Total_rol_adultos_ayores')->nullable();
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
