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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_accion_eventos', function (Blueprint $table) {
            $table->increments('Id_Accion');
            $table->string('ID_evento',10);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->string('Modalidad_calificacion',25)->nullable();
            $table->string('Fuente_informacion',25)->nullable();            
            $table->datetime('F_accion');
            $table->string('Accion',40);
            $table->date('F_Alerta')->nullable();
            $table->string('Enviar',40);
            $table->string('Causal_devolucion_comite',40)->nullable();
            $table->text('Descripcion_accion')->nullable();
            $table->datetime('F_recepcion_doc_origen')->nullable();
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_accion_eventos');
    }
};
