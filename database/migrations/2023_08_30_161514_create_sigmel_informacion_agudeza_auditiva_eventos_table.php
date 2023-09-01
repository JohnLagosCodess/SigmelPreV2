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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_agudeza_auditiva_eventos', function (Blueprint $table) {
            $table->increments('Id_Agudeza_auditiva');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->integer('Oido_Izquierdo');
            $table->integer('Oido_Derecho');
            $table->float('Deficiencia_monoaural_izquierda');
            $table->float('Deficiencia_monoaural_derecha');
            $table->float('Deficiencia_binaural');
            $table->integer('Adicion_tinnitus');
            $table->string('Dx_Principal',2)->nullable();
            $table->integer('Deficiencia');
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
        Schema::dropIfExists('sigmel_informacion_agudeza_auditiva_eventos');
    }
};
