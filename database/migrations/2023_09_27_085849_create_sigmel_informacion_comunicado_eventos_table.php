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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_comunicado_eventos', function (Blueprint $table) {
            $table->increments('Id_Comunicado');
            $table->string('ID_evento', 20);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');            
            $table->text('Ciudad');
            $table->date('F_comunicado');
            $table->text('N_radicado');
            $table->text('Cliente');
            $table->text('Nombre_afiliado');
            $table->text('T_documento');
            $table->string('N_identificacion', 25);
            $table->text('Destinatario');
            $table->text('JRCI_Destinatario')->nullable();
            $table->string('Nombre_destinatario', 100);
            $table->text('Nit_cc');
            $table->text('Direccion_destinatario');
            $table->integer('Telefono_destinatario');
            $table->text('Email_destinatario');
            $table->integer('Id_departamento');
            $table->integer('Id_municipio');
            $table->text('Asunto');
            $table->text('Cuerpo_comunicado');
            $table->integer('Anexos')->nullable();
            $table->integer('Forma_envio');
            $table->string('Elaboro', 100);
            $table->integer('Reviso');
            $table->text('Agregar_copia')->nullable();
            $table->text('JRCI_copia')->nullable();
            $table->string('Firmar_Comunicado', 20)->nullable();
            $table->text('Tipo_descarga')->nullable();
            $table->text('Modulo_creacion')->nullable();
            $table->integer('Reemplazado')->default(0);
            $table->text('Nombre_documento')->nullable();
            $table->enum('Lista_chequeo',['Si','No'])->nullable()->default('No');
            $table->integer('Otro_destinatario')->default(0);
            $table->string('Nombre_usuario', 100);
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_comunicado_eventos');
    }
};
