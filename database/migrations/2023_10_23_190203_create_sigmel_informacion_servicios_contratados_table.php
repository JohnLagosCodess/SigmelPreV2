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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_servicios_contratados', function (Blueprint $table) {
            $table->increments('Id_Servicio_Contratado');
            $table->integer('Id_cliente')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->integer('Id_servicio')->nullable();
            $table->text('Valor_tarifa_servicio')->nullable();
            $table->text('Nro_consecutivo_dictamen_servicio')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_servicios_contratados');
    }
};
