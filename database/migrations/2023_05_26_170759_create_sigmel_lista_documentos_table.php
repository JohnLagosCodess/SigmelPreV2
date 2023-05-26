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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_documentos', function (Blueprint $table) {
            $table->increments('Id_Documento');
            $table->string('Nro_documento', 3);
            $table->string('Nombre_documento', 60);
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo');
            $table->date('F_registro_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_documentos');
    }
};
