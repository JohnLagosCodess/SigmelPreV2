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
            $table->increments('id');
            $table->text('nombre_cliente');
            $table->string('nit', 100);
            $table->text('razon_social');
            $table->text('representante_legal');
            $table->string('telefono_contacto', 12);
            $table->string('correo_contacto', 50);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->text('observacion')->nullable();
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
