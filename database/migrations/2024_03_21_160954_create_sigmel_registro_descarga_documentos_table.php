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
        Schema::connection('sigmel_gestiones')->create('sigmel_registro_descarga_documentos', function (Blueprint $table) {
            $table->increments('Id_registro_documento');
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->integer('Id_servicio')->nullable(); 
            $table->string('ID_evento', 20)->nullable();
            $table->text('Nombre_documento')->nullable();
            $table->text('N_radicado_documento')->nullable();
            $table->date('F_elaboracion_correspondencia')->nullable();
            $table->date('F_descarga_documento')->nullable();
            $table->text('Nombre_usuario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_registro_descarga_documentos');
    }
};
