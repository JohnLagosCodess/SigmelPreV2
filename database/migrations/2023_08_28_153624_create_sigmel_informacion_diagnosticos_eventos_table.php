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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_diagnosticos_eventos', function (Blueprint $table) {
            $table->increments('Id_Diagnosticos_motcali');
            $table->string('ID_evento', 10)->nullable();
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->text('CIE10')->nullable();
            $table->text('Nombre_CIE10')->nullable();
            $table->text('Origen_CIE10')->nullable();
            $table->text('Lateralidad_CIE10')->nullable();
            $table->text('Deficiencia_motivo_califi_condiciones')->nullable();
            $table->enum('Principal', ['Si','No'])->nullable();
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
        Schema::dropIfExists('sigmel_informacion_diagnosticos_eventos');
    }
};
