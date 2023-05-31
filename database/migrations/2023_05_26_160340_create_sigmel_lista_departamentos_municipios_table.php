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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_departamentos_municipios', function (Blueprint $table) {
            $table->increments('Id_municipios');
            $table->integer('Id_departamento')->nullable();
            $table->string('Nombre_departamento', 30)->nullable();
            $table->string('Nombre_municipio', 50)->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo')->nullable();
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_departamentos_municipios');
    }
};
