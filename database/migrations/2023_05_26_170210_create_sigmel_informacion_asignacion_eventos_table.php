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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_asignacion_eventos', function (Blueprint $table) {
            $table->increments('Id_Asignacion');
            $table->string('ID_evento', 10);
            $table->integer('Id_proceso');
            $table->enum('Visible_Nuevo_Proceso', ['Si','No'])->default('Si')->nullable();
            $table->integer('Id_servicio');
            $table->enum('Visible_Nuevo_Servicio', ['Si','No'])->default('Si')->nullable();
            $table->integer('Id_accion');
            $table->text('Descripcion');
            $table->date('F_alerta')->nullable();
            $table->integer('Id_Estado_evento')->nullable();
            $table->date('F_accion')->nullable();
            $table->text('Nombre_profesional')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_asignacion_eventos');
    }
};
