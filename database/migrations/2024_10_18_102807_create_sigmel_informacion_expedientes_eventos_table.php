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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_expedientes_eventos', function (Blueprint $table) {
            $table->increments('Id_expedientes');
            $table->integer('Id_Documento')->nullable();
            $table->string('ID_evento', 20)->nullable();
            $table->text('Nombre_documento')->nullable();
            $table->string('Formato_documento', 7)->nullable();
            $table->integer('Id_servicio')->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo')->nullable();
            $table->integer('Posicion')->nullable();
            $table->enum('Folear', ['Si', 'No'])->nullable();
            $table->text('Nombre_usuario')->nullable();
            $table->date('F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_expedientes_eventos');
    }
};
