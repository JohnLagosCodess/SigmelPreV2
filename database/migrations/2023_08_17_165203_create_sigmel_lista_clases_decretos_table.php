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
        Schema::connection('sigmel_gestiones')->create('sigmel_lista_clases_decretos', function (Blueprint $table) {
            $table->increments('Id_clase');
            $table->integer('Id_tabla');
            $table->string("1A",10)->nullable(); 
            $table->string("1B",10)->nullable();
            $table->string("1C",10)->nullable();
            $table->string("1D",10)->nullable();
            $table->string("1E",10)->nullable();
            $table->string("2A",10)->nullable();
            $table->string("2B",10)->nullable();
            $table->string("2C",10)->nullable();
            $table->string("2D",10)->nullable();
            $table->string("2E",10)->nullable();
            $table->string("3A",10)->nullable();
            $table->string("3B",10)->nullable();
            $table->string("3C",10)->nullable();
            $table->string("3D",10)->nullable();
            $table->string("3E",10)->nullable();
            $table->string("4A",10)->nullable();
            $table->string("4B",10)->nullable();
            $table->string("4C",10)->nullable();
            $table->string("4D",10)->nullable();
            $table->string("4E",10)->nullable();
            $table->string("MSD",10)->nullable();
            $table->enum('Estado', ['Activo','Inactivo'])->default('Activo')->nullable();
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_lista_clases_decretos');
    }
};
