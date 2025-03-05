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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_historial_accion_eventos', function (Blueprint $table) {
            $table->increments('Id_historial_accion');
            $table->integer('Id_Asignacion')->nullable();
            $table->string('ID_evento', 20)->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->integer('Id_servicio')->nullable();            
            $table->integer('Id_accion')->nullable();
            $table->text('Documento')->nullable();
            $table->text('Descripcion')->nullable();
            $table->datetime('F_accion')->nullable();
            $table->integer('Movimiento_automatico')->nullable();
            $table->datetime('F_primer_accion')->nullable();
            $table->text('Nombre_usuario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_historial_accion_eventos');
    }
};
