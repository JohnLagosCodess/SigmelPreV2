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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_libro2_libro3_eventos', function (Blueprint $table) {
            $table->increments('Id_Libros');
            $table->string('ID_evento', 15);
            $table->integer('Id_Asignacion');
            $table->integer('Id_proceso');
            $table->string('Conducta10', 4)->nullable();
            $table->string('Conducta11', 4)->nullable();
            $table->string('Conducta12', 4)->nullable();
            $table->string('Conducta13', 4)->nullable();
            $table->string('Conducta14', 4)->nullable();
            $table->string('Conducta15', 4)->nullable();
            $table->string('Conducta16', 4)->nullable();
            $table->string('Conducta17', 4)->nullable();
            $table->string('Conducta18', 4)->nullable();
            $table->string('Conducta19', 4)->nullable();
            $table->string('Total_conducta', 4)->nullable();            
            $table->string('Comunicacion20', 4)->nullable();
            $table->string('Comunicacion21', 4)->nullable();
            $table->string('Comunicacion22', 4)->nullable();
            $table->string('Comunicacion23', 4)->nullable();
            $table->string('Comunicacion24', 4)->nullable();
            $table->string('Comunicacion25', 4)->nullable();
            $table->string('Comunicacion26', 4)->nullable();
            $table->string('Comunicacion27', 4)->nullable();
            $table->string('Comunicacion28', 4)->nullable();
            $table->string('Comunicacion29', 4)->nullable();
            $table->string('Total_comunicacion', 4)->nullable();
            $table->string('Personal30', 4)->nullable();
            $table->string('Personal31', 4)->nullable();
            $table->string('Personal32', 4)->nullable();
            $table->string('Personal33', 4)->nullable();
            $table->string('Personal34', 4)->nullable();
            $table->string('Personal35', 4)->nullable();
            $table->string('Personal36', 4)->nullable();
            $table->string('Personal37', 4)->nullable();
            $table->string('Personal38', 4)->nullable();
            $table->string('Personal39', 4)->nullable();
            $table->string('Total_personal', 4)->nullable();
            $table->string('Locomocion40', 4)->nullable();
            $table->string('Locomocion41', 4)->nullable();
            $table->string('Locomocion42', 4)->nullable();
            $table->string('Locomocion43', 4)->nullable();
            $table->string('Locomocion44', 4)->nullable();
            $table->string('Locomocion45', 4)->nullable();
            $table->string('Locomocion46', 4)->nullable();
            $table->string('Locomocion47', 4)->nullable();
            $table->string('Locomocion48', 4)->nullable();
            $table->string('Locomocion49', 4)->nullable();
            $table->string('Total_locomocion', 4)->nullable();
            $table->string('Disposicion50', 4)->nullable();
            $table->string('Disposicion51', 4)->nullable();
            $table->string('Disposicion52', 4)->nullable();
            $table->string('Disposicion53', 4)->nullable();
            $table->string('Disposicion54', 4)->nullable();
            $table->string('Disposicion55', 4)->nullable();
            $table->string('Disposicion56', 4)->nullable();
            $table->string('Disposicion57', 4)->nullable();
            $table->string('Disposicion58', 4)->nullable();
            $table->string('Disposicion59', 4)->nullable();
            $table->string('Total_disposicion', 4)->nullable();
            $table->string('Destreza60', 4)->nullable();
            $table->string('Destreza61', 4)->nullable();
            $table->string('Destreza62', 4)->nullable();
            $table->string('Destreza63', 4)->nullable();
            $table->string('Destreza64', 4)->nullable();
            $table->string('Destreza65', 4)->nullable();
            $table->string('Destreza66', 4)->nullable();
            $table->string('Destreza67', 4)->nullable();
            $table->string('Destreza68', 4)->nullable();
            $table->string('Destreza69', 4)->nullable();
            $table->string('Total_destreza', 4)->nullable();
            $table->string('Situacion70', 4)->nullable();
            $table->string('Situacion71', 4)->nullable();
            $table->string('Situacion72', 4)->nullable();
            $table->string('Situacion73', 4)->nullable();
            $table->string('Situacion74', 4)->nullable();
            $table->string('Situacion75', 4)->nullable();
            $table->string('Situacion76', 4)->nullable();
            $table->string('Situacion77', 4)->nullable();
            $table->string('Situacion78', 4)->nullable();
            $table->string('Total_situacion', 4)->nullable();
            $table->string('Total_discapacidad', 4)->nullable();
            $table->string('Orientacion', 4)->nullable();
            $table->string('Idenpendencia_fisica', 4)->nullable();
            $table->string('Desplazamiento', 4)->nullable();
            $table->string('Ocupacional', 4)->nullable();
            $table->string('Integracion', 4)->nullable();
            $table->string('Autosuficiencia', 4)->nullable();
            $table->string('Edad_cronologica_menor', 4)->nullable();
            $table->string('Edad_cronologica_adulto', 4)->nullable();
            $table->string('Total_minusvalia', 4)->nullable();
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
        Schema::dropIfExists('sigmel_informacion_libro2_libro3_eventos');
    }
};
