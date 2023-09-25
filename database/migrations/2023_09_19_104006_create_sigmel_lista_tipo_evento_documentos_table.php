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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_tipo_evento_documentos', function (Blueprint $table) {
            $table->increments('Id_Tipo_documento');
			$table->integer('Id_Tipo_Evento')->nullable();
            $table->text('Tipo_documento')->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo');
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_tipo_evento_documentos');
    }
};
