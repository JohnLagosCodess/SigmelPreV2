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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_clase_riesgos', function (Blueprint $table) {
            $table->increments('Id_Riesgo');
            $table->string('Nombre_riesgo', 3)->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo')->nullable();
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_clase_riesgos');
    }
};
