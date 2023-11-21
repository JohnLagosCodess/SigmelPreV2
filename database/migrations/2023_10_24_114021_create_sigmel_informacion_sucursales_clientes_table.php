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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_sucursales_clientes', function (Blueprint $table) {
            $table->increments('Id_sucursal');
            $table->integer('Id_cliente')->nullable();
            $table->text('Nombre')->nullable();
            $table->text('Gerente')->nullable();
            $table->text('Telefono_principal')->nullable();
            $table->text('Otros_telefonos')->nullable();
            $table->text('Email_principal')->nullable();
            $table->text('Otros_emails')->nullable();
            $table->text('Linea_atencion_principal')->nullable();
            $table->text('Otras_lineas_atencion')->nullable();
            $table->text('Direccion')->nullable();
            $table->integer('Id_Departamento')->nullable();
            $table->integer('Id_Ciudad')->nullable();
            $table->enum('Estado', ['Activo', 'Inactivo'])->default('Activo')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_sucursales_clientes');
    }
};
