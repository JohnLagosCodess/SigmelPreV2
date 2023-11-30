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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_entidades', function (Blueprint $table) {
            $table->increments('Id_Entidad');
            $table->integer('IdTipo_entidad');
            $table->string('Otro_entidad', 50)->nullable();
            $table->string('Nombre_entidad', 100);
            $table->string('Nit_entidad', 20);
            $table->text('Telefonos');
            $table->text('Otros_Telefonos')->nullable();
            $table->text('Emails');
            $table->text('Otros_Emails')->nullable();
            $table->text('Direccion');
            $table->integer('Id_Departamento');
            $table->integer('Id_Ciudad');
            $table->integer('Id_Medio_Noti');
            $table->text('Sucursal');
            $table->text('Dirigido');
            $table->enum('Estado_entidad', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_entidades');
    }
};
