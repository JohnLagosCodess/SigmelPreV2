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
        Schema::connection('sigmel_auditorias')->create('sigmel_auditorias_entidades', function (Blueprint $table) {
            $table->increments('Id');
            $table->integer('Id_Entidad')->nullable();
            $table->integer('IdTipo_entidad')->nullable();
            $table->string('Otro_entidad', 50)->nullable();
            $table->string('Nombre_entidad', 100)->nullable();
            $table->string('Nit_entidad', 20)->nullable();
            $table->text('Telefonos');
            $table->text('Otros_Telefonos');
            $table->text('Emails');
            $table->text('Otros_Emails');
            $table->text('Direccion');
            $table->integer('Id_Departamento')->nullable();
            $table->integer('Id_Ciudad')->nullable();
            $table->integer('Id_Medio_Noti')->nullable();
            $table->text('Sucursal');
            $table->text('Dirigido');
            $table->enum('Estado_entidad', ['activo', 'inactivo'])->default('activo')->nullable();
            $table->integer('id_usuario_sesion')->nullable();
            $table->text('nombre_usuario_sesion')->nullable();
            $table->text('acccion_realizada')->nullable();
            $table->datetime('fecha_registro_accion')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_auditorias_entidades');
    }
};
