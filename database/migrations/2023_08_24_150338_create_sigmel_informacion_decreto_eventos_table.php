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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_decreto_eventos', function (Blueprint $table) {
            $table->increments('Id_decreto');
            $table->text('ID_Evento');
            $table->integer('Id_proceso');
            $table->integer('Id_Asignacion');
            $table->integer('Origen_firme');
            $table->integer('Cobertura');
            $table->integer('Decreto_calificacion');
            $table->string('Numero_dictamen',7);
            $table->text('PCL_anterior')->nullable();
            $table->text('Descripcion_nueva_calificacion')->nullable();
            $table->text('Relacion_documentos')->nullable();
            $table->text('Otros_relacion_doc')->nullable();
            $table->text('Descripcion_enfermedad_actual');
            $table->string('Suma_combinada',10)->nullable();
            $table->string('Total_Deficiencia50',10)->nullable();
            $table->text('Porcentaje_pcl');
            $table->text('Rango_pcl');
            $table->text('Monto_indemnizacion');
            $table->text('Tipo_evento');
            $table->text('Origen');
            $table->date('F_evento');
            $table->date('F_estructuracion');
            $table->text('Requiere_Revision_Pension')->nullable();
            $table->text('Sustentacion_F_estructuracion');
            $table->text('Detalle_calificacion');
            $table->text('Enfermedad_catastrofica')->nullable();
            $table->text('Enfermedad_congenita')->nullable();
            $table->text('Tipo_enfermedad')->nullable();
            $table->text('Requiere_tercera_persona')->nullable();
            $table->text('Requiere_tercera_persona_decisiones')->nullable();
            $table->text('Requiere_dispositivo_apoyo')->nullable();
            $table->text('Justificacion_dependencia')->nullable();
            $table->text('N_radicado')->nullable();  
            $table->text('N_siniestro')->nullable();
            $table->text('Estado_decreto')->nullable();
            $table->string('Modalidad_calificacion',25)->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_decreto_eventos');
    }
};
