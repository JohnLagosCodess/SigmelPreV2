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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_controversia_juntas_eventos', function (Blueprint $table) {
            $table->increments('Id_Contro_Junta');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion')->nullable();
            $table->integer('Id_proceso')->nullable();
            $table->string('Enfermedad_heredada', 45)->nullable();
            $table->date('F_transferencia_enfermedad')->nullable();
            $table->integer('Primer_calificador')->nullable();
            $table->string('Nom_entidad', 150)->nullable();
            $table->integer('N_dictamen_controvertido')->nullable();
            $table->date('F_notifi_afiliado')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_controversia_juntas_eventos');
    }
};
