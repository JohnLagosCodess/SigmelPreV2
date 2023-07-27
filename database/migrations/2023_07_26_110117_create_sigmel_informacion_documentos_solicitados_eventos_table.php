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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_documentos_solicitados_eventos', function (Blueprint $table) {
            $table->increments('Id_Documento_Solicitado');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->date('F_solicitud_documento');
            $table->integer('Id_Documento');
            $table->text('Nombre_documento');
            $table->text('Descripcion');
            $table->integer('Id_solicitante');
            $table->text('Nombre_solicitante');
            $table->date('F_recepcion_documento');
            $table->enum('Estado', ['Activo','Inactivo'])->default('Activo');
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_documentos_solicitados_eventos');
    }
};
