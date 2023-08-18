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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_tablas_1507_decretos', function (Blueprint $table) {
            $table->increments('Id_tabla');
            $table->string('Ident_tabla', 20)->nullable();
            $table->text('Nombre_tabla')->nullable();
            $table->text('FP')->nullable();
            $table->text('CFM1')->nullable();
            $table->text('CFM2')->nullable();
            $table->text('FU')->nullable();
            $table->text('CAT')->nullable();
            $table->enum('Estado', ['Activo','Inactivo'])->default('Activo')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_tablas_1507_decretos');
    }
};
