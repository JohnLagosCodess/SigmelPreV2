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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_pericial_eventos', function (Blueprint $table) {
            $table->increments('Id_Pericial');
            $table->string('ID_evento', 10);
            $table->integer('Id_motivo_solicitud')->nullable();
            $table->string('Tipo_vinculacion', 15)->nullable();
            $table->string('Regimen_salud', 13)->nullable();
            $table->integer('Id_solicitante')->nullable();
            $table->integer('Id_nombre_solicitante')->nullable();
            $table->string('Fuente_informacion', 10)->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_pericial_eventos');
    }
};
