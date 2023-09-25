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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_grupo_documentales', function (Blueprint $table) {
            $table->increments('Id_documental');
            $table->integer('Id_Tipo_Evento')->nullable();
            $table->string('Id_Tipo_documento', 50)->nullable();
            $table->text('Documento')->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo');
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_grupo_documentales');
    }
};
