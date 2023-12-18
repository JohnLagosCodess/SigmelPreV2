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
            $table->integer('Tipo_vinculacion')->nullable();
            $table->integer('Regimen_salud')->nullable();
            $table->integer('Id_solicitante')->nullable();
            $table->integer('Id_nombre_solicitante')->nullable();
            $table->text('Nombre_solicitante')->nullable();
            $table->integer('Fuente_informacion')->nullable();
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
