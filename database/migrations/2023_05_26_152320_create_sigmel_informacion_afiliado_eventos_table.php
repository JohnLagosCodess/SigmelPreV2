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
            $table->string('Tipo_documento', 7);
            $table->string('Nro_identificacion', 25);
            $table->date('F_nacimiento');
            $table->string('Edad', 3)->nullable();
            $table->string('Genero', 20)->nullable();
            $table->string('Email', 20)->nullable();
            $table->string('Telefono_contacto', 22);
            $table->string('Estado_civil', 10)->nullable();
            $table->string('Nivel_escolar', 15)->nullable();
            $table->enum('Apoderado', ['Si', 'No'])->nullable();
            $table->text('Nombre_apoderado')->nullable();
            $table->string('Nro_identificacion_apoderado', 25)->nullable();
            $table->integer('Id_dominancia')->nullable();
            $table->text('Direccion');
            $table->integer('Id_departamento')->nullable();
            $table->integer('Id_municipio')->nullable();
            $table->text('Ocupacion')->nullable();
            $table->string('Tipo_afiliado', 15)->nullable();
            $table->text('Ibc')->nullable();
            $table->integer('Id_eps')->nullable();
            $table->integer('Id_afp')->nullable();
            $table->integer('Id_arl')->nullable();
            $table->enum('Activo', ['Si', 'No']);
            $table->text('Nombre_usuario');
            $table->date('F_registro');
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
