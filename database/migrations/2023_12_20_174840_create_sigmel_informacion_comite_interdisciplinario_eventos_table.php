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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_comite_interdisciplinario_eventos', function (Blueprint $table) {
            $table->increments('Id_com_inter');
            $table->string('ID_evento', 10);
            $table->integer('Id_proceso');
            $table->integer('Id_Asignacion');
            $table->string('Visar', 5);
            $table->text('Profesional_comite')->nullable();
            $table->date('F_visado_comite')->nullable();
            $table->text('Oficio_pcl')->nullable();
            $table->text('Oficio_incapacidad')->nullable();
            $table->text('Destinatario_principal')->nullable();
            $table->text('Otro_destinatario')->nullable();
            $table->text('Tipo_destinatario')->nullable();
            $table->text('Nombre_dest_principal')->nullable();   
            $table->text('Nombre_dest_principal_afi_empl')->nullable(); 
            $table->text('Nombre_destinatario')->nullable();
            $table->text('Nit_cc')->nullable();
            $table->text('Direccion_destinatario')->nullable();
            $table->text('Telefono_destinatario')->nullable();
            $table->text('Email_destinatario')->nullable();
            $table->text('Departamento_destinatario')->nullable();
            $table->text('Ciudad_destinatario')->nullable();
            $table->text('Asunto');
            $table->text('Cuerpo_comunicado');
            $table->text('Copia_empleador')->nullable();
            $table->text('Copia_eps')->nullable();
            $table->text('Copia_afp')->nullable();
            $table->text('Copia_arl')->nullable();
            $table->text('Copia_jr')->nullable();
            $table->text('Cual_jr')->nullable();
            $table->text('Copia_jn')->nullable();
            $table->integer('Anexos')->nullable();
            $table->text('Elaboro')->nullable();
            $table->text('Reviso')->nullable();
            $table->string('Firmar', 5)->nullable();
            $table->text('Ciudad')->nullable();
            $table->text('F_correspondecia')->nullable();
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
        Schema::dropIfExists('sigmel_informacion_comite_interdisciplinario_eventos');
    }
};
