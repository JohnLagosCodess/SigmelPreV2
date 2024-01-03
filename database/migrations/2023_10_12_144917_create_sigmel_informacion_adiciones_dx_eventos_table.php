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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_adiciones_dx_eventos', function (Blueprint $table) {
            $table->increments('Id_Adiciones_Dx');
            $table->string('ID_evento', 10)->nullable();
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->integer('Id_Dto_ATEL');
            $table->enum('Activo', ['Si','No'])->nullable();
            $table->integer('Tipo_evento')->nullable();
            $table->text('Relacion_documentos')->nullable();
            $table->text('Otros_relacion_documentos')->nullable();
            $table->text('Sustentacion_Adicion_Dx');
            $table->integer('Origen');
            $table->text('N_radicado')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_adiciones_dx_eventos');
    }
};
