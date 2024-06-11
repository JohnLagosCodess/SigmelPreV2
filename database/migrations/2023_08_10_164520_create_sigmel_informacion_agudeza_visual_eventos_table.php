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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_agudeza_visual_eventos', function (Blueprint $table) {
            $table->increments('Id_agudeza');
            $table->string('ID_evento', 20);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->enum('Ceguera_Total',['Si', 'No'])->nullable();
            $table->string('Agudeza_Ojo_Izq',7)->nullable();
            $table->string('Agudeza_Ojo_Der',7)->nullable();
            $table->string('Agudeza_Ambos_Ojos',7)->nullable();
            $table->string('PAVF',7)->nullable();
            $table->string('DAV',7)->nullable();
            $table->string('Campo_Visual_Ojo_Izq',7)->nullable();
            $table->string('Campo_Visual_Ojo_Der',7)->nullable();
            $table->string('Campo_Visual_Ambos_Ojos',7)->nullable();
            $table->string('CVF',7)->nullable();
            $table->string('DCV',7)->nullable();
            $table->string('DSV',7)->nullable();
            $table->string('Dx_Principal',2)->nullable();
            $table->string('Deficiencia',7)->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_agudeza_visual_eventos');
    }
};
