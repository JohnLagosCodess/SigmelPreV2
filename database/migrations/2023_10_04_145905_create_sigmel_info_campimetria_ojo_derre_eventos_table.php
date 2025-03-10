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
        Schema::connection('sigmel_gestiones')->create('sigmel_info_campimetria_ojo_derre_eventos', function (Blueprint $table) {
            $table->increments('Id_info');
            $table->integer('Id_agudeza')->nullable();
            $table->string('InfoFila1', 10)->nullable();
            $table->string('InfoFila2', 10)->nullable();
            $table->string('InfoFila3', 10)->nullable();
            $table->string('InfoFila4', 10)->nullable();
            $table->string('InfoFila5', 10)->nullable();
            $table->string('InfoFila6', 10)->nullable();
            $table->string('InfoFila7', 10)->nullable();
            $table->string('InfoFila8', 10)->nullable();
            $table->string('InfoFila9', 10)->nullable();
            $table->string('InfoFila10', 10)->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_info_campimetria_ojo_derre_eventos');
    }
};
