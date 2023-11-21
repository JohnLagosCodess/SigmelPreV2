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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_pagos_honorarios_eventos', function (Blueprint $table) {
            $table->increments('Id_pago');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->integer('Tipo_pago')->nullable();
            $table->date('F_solicitud_pago')->nullable();
            $table->integer('Pago_junta')->nullable();
            $table->string('N_orden_pago', 50)->nullable();
            $table->text('Valor_pagado', 50)->nullable();
            $table->date('F_pago_honorarios')->nullable();
            $table->date('F_pago_radicacion')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_pagos_honorarios_eventos');
    }
};
