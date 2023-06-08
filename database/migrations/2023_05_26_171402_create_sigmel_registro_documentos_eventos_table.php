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
        Schema::connection('sigmel_gestiones')->create('sigmel_registro_documentos_eventos', function (Blueprint $table) {
            $table->increments('Id_Registro_Documento');
            $table->integer('Id_Documento');
            $table->string('ID_evento', 10);
            $table->text('Nombre_documento');
            $table->string('Formato_documento', 7);
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo');
            $table->date('F_cargue_documento');
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_registro_documentos_eventos');
    }
};
