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
        Schema::connection('sigmel_gestiones')->create('sigmel_historico_empresas_afiliados', function (Blueprint $table) {
            $table->increments('Id_HistoricoLaboral');
            $table->string('Nro_identificacion', 25)->nullable();
            $table->enum('Tipo_empleado', ['Empleado actual', 'Independiente', 'Beneficiario']);
            $table->integer('Id_arl')->nullable();
            $table->text('Empresa');
            $table->string('Nit_o_cc', 20);
            $table->string('Telefono_empresa', 10)->nullable();
            $table->text('Email')->nullable();
            $table->text('Direccion')->nullable();
            $table->integer('Id_departamento')->nullable();
            $table->integer('Id_municipio')->nullable();
            $table->integer('Id_actividad_economica')->nullable();
            $table->integer('Id_clase_riesgo')->nullable();
            $table->text('Persona_contacto')->nullable();
            $table->string('Telefono_persona_contacto', 22)->nullable();
            $table->integer('Id_codigo_ciuo')->nullable();
            $table->date('F_ingreso')->nullable();
            $table->text('Cargo')->nullable();
            $table->text('Funciones_cargo')->nullable();
            $table->string('Antiguedad_empresa', 3)->nullable();
            $table->string('Antiguedad_cargo_empresa')->nullable();
            $table->date('F_retiro')->nullable();
            $table->text('Descripcion')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_historico_empresas_afiliados');
    }
};
