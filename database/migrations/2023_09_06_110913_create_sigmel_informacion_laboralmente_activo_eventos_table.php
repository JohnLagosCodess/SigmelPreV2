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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_laboralmente_activo_eventos', function (Blueprint $table) {
            $table->increments('Id_Laboral_activo');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->string('Restricciones_rol', 4)->nullable();
            $table->string('Autosuficiencia_economica', 4)->nullable();
            $table->float('Edad_cronologica_menor')->nullable();
            $table->float('Edad_cronologica')->nullable();
            $table->float('Total_rol_laboral')->nullable();
            $table->float('Aprendizaje_mirar')->nullable();
            $table->float('Aprendizaje_escuchar')->nullable();
            $table->float('Aprendizaje_aprender')->nullable();
            $table->float('Aprendizaje_calcular')->nullable();
            $table->float('Aprendizaje_pensar')->nullable();
            $table->float('Aprendizaje_leer')->nullable();
            $table->float('Aprendizaje_escribir')->nullable();
            $table->float('Aprendizaje_matematicos')->nullable();
            $table->float('Aprendizaje_resolver')->nullable();
            $table->float('Aprendizaje_tareas')->nullable();
            $table->float('Aprendizaje_total')->nullable();            
            $table->float('Comunicacion_verbales')->nullable();
            $table->float('Comunicacion_noverbales')->nullable();
            $table->float('Comunicacion_formal')->nullable();
            $table->float('Comunicacion_escritos')->nullable();
            $table->float('Comunicacion_habla')->nullable();
            $table->float('Comunicacion_produccion')->nullable();
            $table->float('Comunicacion_mensajes')->nullable();
            $table->float('Comunicacion_conversacion')->nullable();
            $table->float('Comunicacion_discusiones')->nullable();
            $table->float('Comunicacion_dispositivos')->nullable();
            $table->float('Comunicacion_total')->nullable();            
            $table->float('Movilidad_cambiar_posturas')->nullable();
            $table->float('Movilidad_mantener_posicion')->nullable();
            $table->float('Movilidad_objetos')->nullable();
            $table->float('Movilidad_uso_mano')->nullable();
            $table->float('Movilidad_mano_brazo')->nullable();
            $table->float('Movilidad_Andar')->nullable();
            $table->float('Movilidad_desplazarse')->nullable();
            $table->float('Movilidad_equipo')->nullable();
            $table->float('Movilidad_transporte')->nullable();
            $table->float('Movilidad_conduccion')->nullable();
            $table->float('Movilidad_total')->nullable();            
            $table->float('Cuidado_lavarse')->nullable();
            $table->float('Cuidado_partes_cuerpo')->nullable();
            $table->float('Cuidado_higiene')->nullable();
            $table->float('Cuidado_vestirse')->nullable();
            $table->float('Cuidado_quitarse')->nullable();
            $table->float('Cuidado_ponerse_calzado')->nullable();
            $table->float('Cuidado_comer')->nullable();
            $table->float('Cuidado_beber')->nullable();
            $table->float('Cuidado_salud')->nullable();
            $table->float('Cuidado_dieta')->nullable();
            $table->float('Cuidado_total')->nullable();            
            $table->float('Domestica_vivir')->nullable();
            $table->float('Domestica_bienes')->nullable();
            $table->float('Domestica_comprar')->nullable();
            $table->float('Domestica_comidas')->nullable();
            $table->float('Domestica_quehaceres')->nullable();
            $table->float('Domestica_limpieza')->nullable();
            $table->float('Domestica_objetos')->nullable();
            $table->float('Domestica_ayudar')->nullable();
            $table->float('Domestica_mantenimiento')->nullable();
            $table->float('Domestica_animales')->nullable();
            $table->float('Domestica_total')->nullable();
            $table->float('Total_otras_areas')->nullable();
            $table->float('Total_laboral_otras_areas')->nullable(); 
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
        Schema::dropIfExists('sigmel_informacion_laboralmente_activo_eventos');
    }
};
