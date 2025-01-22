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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_ans_clientes', function (Blueprint $table) {
            $table->increments('Id_ans');
            $table->integer('Id_cliente')->nullable();
            $table->text('Nombre')->nullable();
            $table->integer('Servicio')->nullable();
            $table->integer('Accion')->nullable();
            $table->text('Valor')->nullable();
            $table->integer('Unidad')->nullable();
            $table->text('Porcentaje_Alerta_Naranja')->nullable();
            $table->text('Porcentaje_Alerta_Roja')->nullable();
            $table->integer('Estado')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_ans_clientes');
    }
};
