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
        Schema::create('sigmel_control_sesiones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usuario_id')->nullable();
            $table->boolean('bandera')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->date('fecha_inicio_sesion')->nullable();
            $table->time('hora_inicio_sesion')->nullable();
            $table->date('fecha_cerro_sesion')->nullable();
            $table->time('hora_cerro_sesion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_control_sesiones');
    }
};
