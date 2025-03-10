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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_alertas_ans_eventos', function (Blueprint $table) {
            $table->increments('Id_alerta_ans');
            $table->string('ID_evento', 20)->nullable();
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_ans')->nullable();
            $table->dateTime('Fecha_alerta_naranja')->nullable();
            $table->dateTime('Fecha_alerta_roja')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_alertas_ans_eventos');
    }
};
