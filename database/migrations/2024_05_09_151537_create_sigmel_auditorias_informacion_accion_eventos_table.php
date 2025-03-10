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
        Schema::connection('sigmel_auditorias')->create('sigmel_auditorias_informacion_accion_eventos', function (Blueprint $table) {
            $table->increments('Id_Aud_accion');
            $table->integer('Aud_Id_Accion')->nullable();
            $table->string('Aud_ID_evento',20)->nullable();
            $table->integer('Aud_Id_Asignacion')->nullable();
            $table->integer('Aud_Id_proceso')->nullable();
            $table->string('Aud_Modalidad_calificacion',25)->nullable();
            $table->string('Aud_Fuente_informacion',25)->nullable();            
            $table->datetime('Aud_F_accion')->nullable();
            $table->string('Aud_Accion',40)->nullable();
            $table->date('Aud_F_Alerta')->nullable();
            $table->string('Aud_Enviar',40)->nullable();
            $table->text('Aud_Estado_Facturacion')->nullable();
            $table->string('Aud_Causal_devolucion_comite',40)->nullable();
            $table->datetime('Aud_F_devolucion_comite')->nullable();
            $table->text('Aud_Descripcion_accion')->nullable();
            $table->datetime('Aud_F_recepcion_doc_origen')->nullable();
			$table->datetime('Aud_F_asignacion_dto')->nullable();
            $table->datetime('Aud_F_calificacion_servicio')->nullable();
            $table->datetime('Aud_F_asignacion_pronu_juntas')->nullable();
            $table->date('Aud_F_cierre')->nullable();
            $table->text('Aud_Nombre_usuario')->nullable();
            $table->date('Aud_F_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_auditorias_informacion_accion_eventos');
    }
};
