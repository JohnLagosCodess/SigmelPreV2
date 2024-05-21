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
            $table->string('ID_evento', 20)->nullable();
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->date('F_solicitud_documento')->nullable();
            $table->integer('Id_Documento')->nullable();
            $table->text('Nombre_documento')->nullable();
            $table->text('Descripcion')->nullable();
            $table->integer('Id_solicitante')->nullable();
            $table->text('Nombre_solicitante')->nullable();
            $table->date('F_recepcion_documento')->nullable();
            $table->string('Articulo_12', 45)->nullable();
            $table->integer('Grupo_documental')->default(0)->nullable();
            $table->enum('Aporta_documento', ['Si','No'])->nullable();
            $table->enum('Estado', ['Activo','Inactivo'])->default('Activo')->nullable();
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
