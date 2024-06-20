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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_agudeza_visualre_eventos', function (Blueprint $table) {
            $table->increments('Id_agudeza_re');
            $table->string('ID_evento_re', 20);
            $table->integer('Id_Asignacion_re');
            $table->integer('Id_proceso_re');
            $table->enum('Ceguera_Total_re',['Si', 'No'])->nullable();
            $table->string('Agudeza_Ojo_Izq_re',7)->nullable();
            $table->string('Agudeza_Ojo_Der_re',7)->nullable();
            $table->string('Agudeza_Ambos_Ojos_re',7)->nullable();
            $table->string('PAVF_re',7)->nullable();
            $table->string('DAV_re',7)->nullable();
            $table->string('Campo_Visual_Ojo_Izq_re',7)->nullable();
            $table->string('Campo_Visual_Ojo_Der_re',7)->nullable();
            $table->string('Campo_Visual_Ambos_Ojos_re',7)->nullable();
            $table->string('CVF_re',7)->nullable();
            $table->string('DCV_re',7)->nullable();
            $table->string('DSV_re',7)->nullable();
            $table->string('Dx_Principal_re',2)->nullable();
            $table->string('Deficiencia_re',7)->nullable();
            $table->enum('Estado',['Activo', 'Inactivo'])->default('Activo')->nullable();
            $table->enum('Estado_Recalificacion',['Activo', 'Inactivo'])->default('Activo')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_agudeza_visualre_eventos');
    }
};
