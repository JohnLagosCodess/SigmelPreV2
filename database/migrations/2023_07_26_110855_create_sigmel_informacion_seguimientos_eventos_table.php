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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_seguimientos_eventos', function (Blueprint $table) {
            $table->increments('Id_Seguimiento');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->date('F_seguimiento');
            $table->text('Causal_seguimiento');
            $table->text('Descripcion_seguimiento');
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_seguimientos_eventos');
    }
};
