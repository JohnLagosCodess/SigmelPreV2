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
        Schema::connection('sigmel_gestiones')->create('sigmel_historial_acciones_eventos', function (Blueprint $table) {
            $table->increments('Id_Historial_Accion');
            $table->string('ID_evento', 10);
            $table->date('F_accion')->nullable();
            $table->text('Nombre_usuario')->nullable();
            $table->text('Accion_realizada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_historial_acciones_eventos');
    }
};
