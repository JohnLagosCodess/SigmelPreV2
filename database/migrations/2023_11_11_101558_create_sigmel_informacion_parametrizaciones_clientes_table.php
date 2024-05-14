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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_parametrizaciones_clientes', function (Blueprint $table) {
            $table->increments('Id_parametrizacion');
            $table->integer('Id_cliente');
            $table->integer('Id_proceso')->nullable();
            $table->date('F_creacion_movimiento')->nullable();
            $table->integer('Servicio_asociado')->nullable();
            $table->integer('Estado')->nullable();
            $table->integer('Accion_ejecutar')->nullable();
            $table->integer('Accion_antecesora')->nullable();
            $table->enum('Modulo_nuevo', ['Si', 'No'])->nullable();
            $table->enum('Modulo_consultar', ['Si', 'No'])->nullable();
            $table->enum('Bandeja_trabajo', ['Si', 'No'])->nullable();
            $table->enum('Modulo_principal', ['Si', 'No'])->nullable();
            $table->enum('Detiene_tiempo_gestion', ['Si', 'No'])->nullable();
            $table->integer('Equipo_trabajo')->nullable();
            $table->integer('Profesional_asignado')->nullable();
            $table->enum('Enviar_a_bandeja_trabajo_destino', ['Si', 'No'])->nullable();
            $table->integer('Bandeja_trabajo_destino')->nullable();
            $table->text('Estado_facturacion')->nullable();
            $table->text('Tiempo_alerta')->nullable();
            $table->text('Porcentaje_alerta_naranja')->nullable();
            $table->text('Porcentaje_alerta_roja')->nullable();
            $table->enum('Status_parametrico', ['Activo', 'Inactivo'])->nullable();
            $table->text('Motivo_descripcion_movimiento')->nullable();
            $table->text('Nombre_usuario')->nullable();
            $table->date('F_actualizacion_movimiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_parametrizaciones_clientes');
    }
};
