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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_pronunciamiento_eventos', function (Blueprint $table) {
            $table->increments('Id_Pronuncia');
            $table->string('ID_evento', 10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->integer('Id_primer_calificador')->nullable();
            $table->integer('Id_nombre_calificador')->nullable();
            $table->string('Nit_calificador', 20)->nullable();
            $table->string('Dir_calificador', 80)->nullable();
            $table->string('Email_calificador', 80)->nullable();
            $table->string('Telefono_calificador', 20)->nullable();
            $table->string('Depar_calificador', 80)->nullable();
            $table->string('Ciudad_calificador', 80)->nullable();
            $table->integer('Id_tipo_pronunciamiento')->nullable();
            $table->integer('Id_tipo_evento')->nullable();
            $table->integer('Id_tipo_origen')->nullable();
            $table->date('Fecha_evento')->nullable();
            $table->string('Dictamen_calificador', 50)->nullable();
            $table->date('Fecha_calificador')->nullable();
            $table->date('Fecha_estruturacion')->nullable();
            $table->string('Porcentaje_pcl', 5)->nullable();
            $table->string('Rango_pcl', 20)->nullable();
            $table->string('Decision', 12)->nullable();
            $table->date('Fecha_pronuncia')->nullable();
            $table->text('Asunto_cali')->nullable();
            $table->text('Sustenta_cali')->nullable();
            $table->string('Copia_afiliado', 10)->nullable();
            $table->string('Copia_empleador', 10)->nullable();
            $table->string('Copia_eps', 4)->nullable();
            $table->string('Copia_afp', 4)->nullable();
            $table->string('Copia_arl', 4)->nullable();
            $table->string('Copia_junta_regional', 10)->nullable();
            $table->string('Copia_junta_nacional', 10)->nullable();
            $table->integer('Junta_regional_cual')->nullable();
            $table->string('N_anexos', 20)->nullable();
            $table->string('Elaboro_pronuncia', 100)->nullable();
            $table->string('Reviso_pronuncia', 100)->nullable();
            $table->string('Ciudad_correspon', 100)->nullable();
            $table->string('N_radicado', 22)->nullable();
            $table->string('Firmar', 7)->nullable();
            $table->date('Fecha_correspondencia')->nullable();
            $table->text('Archivo_pronuncia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_pronunciamiento_eventos');
    }
};
