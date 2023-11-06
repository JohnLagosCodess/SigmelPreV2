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
        Schema::connection('sigmel_gestiones')->create('sigmel_clientes', function (Blueprint $table) {
            $table->increments('Id_cliente');
            $table->integer('Tipo_cliente');
            $table->text('Nombre_cliente');
            $table->string('Nit', 100);
            $table->text('Telefono_principal');
            $table->text('Otros_telefonos')->nullable();
            $table->text('Email_principal');
            $table->text('Otros_emails')->nullable();
            $table->text('Linea_atencion_principal');
            $table->text('Otras_lineas_atencion')->nullable();
            $table->text('Direccion');
            $table->integer('Id_Departamento');
            $table->integer('Id_Ciudad');
            $table->enum('Estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->text('Codigo_cliente');
            $table->text('Logo_cliente')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_clientes');
    }
};
