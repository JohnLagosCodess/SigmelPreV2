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
            $table->string('ID_evento', 20);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->string('Restricciones_rol', 4)->nullable();
            $table->string('Autosuficiencia_economica', 4)->nullable();
            $table->string('Edad_cronologica_menor', 4)->nullable();
            $table->string('Edad_cronologica', 4)->nullable();
            $table->string('Total_rol_laboral', 4)->nullable();
            $table->string('Aprendizaje_mirar', 4)->nullable();
            $table->string('Aprendizaje_escuchar', 4)->nullable();
            $table->string('Aprendizaje_aprender', 4)->nullable();
            $table->string('Aprendizaje_calcular', 4)->nullable();
            $table->string('Aprendizaje_pensar', 4)->nullable();
            $table->string('Aprendizaje_leer', 4)->nullable();
            $table->string('Aprendizaje_escribir', 4)->nullable();
            $table->string('Aprendizaje_matematicos', 4)->nullable();
            $table->string('Aprendizaje_resolver', 4)->nullable();
            $table->string('Aprendizaje_tareas', 4)->nullable();
            $table->string('Aprendizaje_total', 4)->nullable();            
            $table->string('Comunicacion_verbales', 4)->nullable();
            $table->string('Comunicacion_noverbales', 4)->nullable();
            $table->string('Comunicacion_formal', 4)->nullable();
            $table->string('Comunicacion_escritos', 4)->nullable();
            $table->string('Comunicacion_habla', 4)->nullable();
            $table->string('Comunicacion_produccion', 4)->nullable();
            $table->string('Comunicacion_mensajes', 4)->nullable();
            $table->string('Comunicacion_conversacion', 4)->nullable();
            $table->string('Comunicacion_discusiones', 4)->nullable();
            $table->string('Comunicacion_dispositivos', 4)->nullable();
            $table->string('Comunicacion_total', 4)->nullable();            
            $table->string('Movilidad_cambiar_posturas', 4)->nullable();
            $table->string('Movilidad_mantener_posicion', 4)->nullable();
            $table->string('Movilidad_objetos', 4)->nullable();
            $table->string('Movilidad_uso_mano', 4)->nullable();
            $table->string('Movilidad_mano_brazo', 4)->nullable();
            $table->string('Movilidad_Andar', 4)->nullable();
            $table->string('Movilidad_desplazarse', 4)->nullable();
            $table->string('Movilidad_equipo', 4)->nullable();
            $table->string('Movilidad_transporte', 4)->nullable();
            $table->string('Movilidad_conduccion', 4)->nullable();
            $table->string('Movilidad_total', 4)->nullable();            
            $table->string('Cuidado_lavarse', 4)->nullable();
            $table->string('Cuidado_partes_cuerpo', 4)->nullable();
            $table->string('Cuidado_higiene', 4)->nullable();
            $table->string('Cuidado_vestirse', 4)->nullable();
            $table->string('Cuidado_quitarse', 4)->nullable();
            $table->string('Cuidado_ponerse_calzado', 4)->nullable();
            $table->string('Cuidado_comer', 4)->nullable();
            $table->string('Cuidado_beber', 4)->nullable();
            $table->string('Cuidado_salud', 4)->nullable();
            $table->string('Cuidado_dieta', 4)->nullable();
            $table->string('Cuidado_total', 4)->nullable();            
            $table->string('Domestica_vivir', 4)->nullable();
            $table->string('Domestica_bienes', 4)->nullable();
            $table->string('Domestica_comprar', 4)->nullable();
            $table->string('Domestica_comidas', 4)->nullable();
            $table->string('Domestica_quehaceres', 4)->nullable();
            $table->string('Domestica_limpieza', 4)->nullable();
            $table->string('Domestica_objetos', 4)->nullable();
            $table->string('Domestica_ayudar', 4)->nullable();
            $table->string('Domestica_mantenimiento', 4)->nullable();
            $table->string('Domestica_animales', 4)->nullable();
            $table->string('Domestica_total', 4)->nullable();
            $table->string('Total_otras_areas', 4)->nullable();
            $table->string('Total_laboral_otras_areas', 4)->nullable(); 
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
