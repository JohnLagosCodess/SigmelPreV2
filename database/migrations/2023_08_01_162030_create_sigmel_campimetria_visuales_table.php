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
        Schema::connection('sigmel_gestiones')->create('sigmel_campimetria_visuales', function (Blueprint $table) {
            $table->increments('Id_Fila');
            $table->string('Fila1', 10)->nullable();
            $table->string('Fila2', 10)->nullable();
            $table->string('Fila3', 10)->nullable();
            $table->string('Fila4', 10)->nullable();
            $table->string('Fila5', 10)->nullable();
            $table->string('Fila6', 10)->nullable();
            $table->string('Fila7', 10)->nullable();
            $table->string('Fila8', 10)->nullable();
            $table->string('Fila9', 10)->nullable();
            $table->string('Fila10', 10)->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_campimetria_visuales');
    }
};
