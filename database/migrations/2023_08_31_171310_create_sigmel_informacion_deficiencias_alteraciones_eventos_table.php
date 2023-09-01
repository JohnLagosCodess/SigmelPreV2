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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_deficiencias_alteraciones_eventos', function (Blueprint $table) {
            $table->increments('Id_Deficiencia');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->integer('Id_tabla')->nullable();
            $table->text('FP')->nullable();
            $table->text('CFM1')->nullable();
            $table->text('CFM2')->nullable();
            $table->text('FU')->nullable();
            $table->text('CAT')->nullable();
            $table->text('Clase_Final')->nullable();
            $table->enum('Dx_Principal', ['Si','No'])->nullable();
            $table->enum('MSD', ['Si','No', 'N/A'])->nullable();
            $table->text('Deficiencia')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_deficiencias_alteraciones_eventos');
    }
};
