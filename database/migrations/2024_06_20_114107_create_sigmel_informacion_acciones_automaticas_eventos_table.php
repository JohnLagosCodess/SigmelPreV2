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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_acciones_automaticas_eventos', function (Blueprint $table) {
            $table->increments('Id_accion_automatica');    
            $table->integer('Id_Asignacion')->nullable();
            $table->string('ID_evento', 20)->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->integer('Id_servicio')->nullable(); 
            $table->integer('Id_cliente')->nullable();           
            $table->integer('Accion_automatica')->nullable();
            $table->integer('Id_Estado_evento_automatico')->nullable();
            $table->datetime('F_accion')->nullable();
            $table->integer('Id_profesional_automatico')->nullable();
            $table->text('Nombre_profesional_automatico')->nullable();
            $table->date('F_movimiento_automatico')->nullable();
            $table->text('Estado_accion_automatica')->nullable();              
            $table->text('Nombre_usuario')->nullable();  
            $table->date('F_registro')->nullable();       

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_acciones_automaticas_eventos');
    }
};
