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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_afiliado_eventos', function (Blueprint $table) {
            $table->increments('Id_Afiliado');
            $table->string('ID_evento', 10);
            $table->string('Nombre_afiliado', 100);
            $table->integer('Tipo_documento');
            $table->string('Nro_identificacion', 25);
            $table->date('F_nacimiento');
            $table->string('Edad', 3)->nullable();
            $table->integer('Genero')->nullable();
            $table->text('Email')->nullable();
            $table->string('Telefono_contacto', 120);
            $table->integer('Estado_civil')->nullable();
            $table->integer('Nivel_escolar')->nullable();
            $table->enum('Apoderado', ['Si', 'No'])->nullable();
            $table->text('Nombre_apoderado')->nullable();
            $table->string('Nro_identificacion_apoderado', 25)->nullable();
            $table->integer('Id_dominancia')->nullable();
            $table->text('Direccion');
            $table->integer('Id_departamento')->nullable();
            $table->integer('Id_municipio')->nullable();
            $table->text('Ocupacion')->nullable();
            $table->integer('Tipo_afiliado')->nullable();
            $table->text('Ibc')->nullable();
            $table->integer('Id_eps')->nullable();
            $table->integer('Id_afp')->nullable();
            $table->integer('Id_arl')->nullable();
            $table->enum('Activo', ['Si', 'No']);
            $table->string('Nombre_afiliado_benefi', 100)->nullable();
            $table->integer('Tipo_documento_benefi')->nullable();
            $table->string('Nro_identificacion_benefi', 25)->nullable();
            $table->text('Direccion_benefi')->nullable();
            $table->integer('Id_departamento_benefi')->nullable();
            $table->integer('Id_municipio_benefi')->nullable();
            $table->text('Medio_notificacion')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
            $table->date('F_actualizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_afiliado_eventos');
    }
};
