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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_cie_diagnosticos', function (Blueprint $table) {
            $table->increments('Id_Cie_diagnostico');
            $table->text('CIE10')->nullable();
            $table->text('Descripcion_diagnostico')->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo')->nullable();            
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_cie_diagnosticos');
    }
};
