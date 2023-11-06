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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_firmas_clientes', function (Blueprint $table) {
            $table->increments('Id_firma');
            $table->integer('Id_cliente')->nullable();
            $table->text('Nombre_firmante')->nullable();
            $table->text('Cargo_firmante')->nullable();
            $table->mediumText('Firma')->nullable();
            $table->text('Url')->nullable();
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
        Schema::dropIfExists('sigmel_informacion_firmas_clientes');
    }
};
