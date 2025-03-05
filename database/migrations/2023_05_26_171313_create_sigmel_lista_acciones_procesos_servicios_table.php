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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_acciones_procesos_servicios', function (Blueprint $table) {
            $table->increments('Id_Accion');
            $table->string('Nombre_accion', 30);
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_acciones_procesos_servicios');
    }
};
