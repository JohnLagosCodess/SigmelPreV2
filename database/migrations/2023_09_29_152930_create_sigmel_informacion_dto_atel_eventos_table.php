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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_dto_atel_eventos', function (Blueprint $table) {
            $table->increments('Id_Dto_ATEL');
            $table->string('ID_evento', 10)->nullable();
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->enum('Activo', ['Si','No'])->nullable();
            $table->integer('Tipo_evento')->nullable();
            $table->date("Fecha_dictamen")->nullable();
            $table->string('Numero_dictamen',7)->nullable();
            $table->integer('Tipo_accidente')->nullable();
            $table->date("Fecha_evento")->nullable();
            $table->time('Hora_evento')->nullable();
            $table->integer('Grado_severidad')->nullable();
            $table->enum('Mortal', ['Si','No'])->nullable();
            $table->date("Fecha_fallecimiento")->nullable();
            $table->text("Descripcion_FURAT")->nullable();
            $table->integer('Factor_riesgo')->nullable();
            $table->integer('Tipo_lesion')->nullable();
            $table->integer('Parte_cuerpo_afectada')->nullable();
            $table->date("Fecha_diagnostico_enfermedad")->nullable();
            $table->enum('Enfermedad_heredada', ['Si','No'])->nullable();
            $table->text("Nombre_entidad_hereda")->nullable();
            $table->text("Justificacion_revision_origen")->nullable();
            $table->text('Relacion_documentos')->nullable();
            $table->text('Otros_relacion_documentos')->nullable();
            $table->text('Sustentacion');
            $table->integer('Origen');
            $table->text('N_radicado')->nullable();            
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_dto_atel_eventos');
    }
};
