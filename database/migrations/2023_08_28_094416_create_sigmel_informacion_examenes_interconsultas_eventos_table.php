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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_examenes_interconsultas_eventos', function (Blueprint $table) {
            $table->increments('Id_Examenes_interconsultas');
            $table->string('ID_evento', 10)->nullable();
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->date('F_examen_interconsulta')->nullable();
            $table->text('Nombre_examen_interconsulta')->nullable();
            $table->text('Descripcion_resultado')->nullable();
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
        Schema::dropIfExists('sigmel_informacion_examenes_interconsultas_eventos');
    }
};
