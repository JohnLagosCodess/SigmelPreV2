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
        Schema::connection('sigmel_gestiones')->create('sigmel_calendarios', function (Blueprint $table) {
            $table->increments('IDDiaCalendario');
            $table->timestamp('FechaRegistro')->nullable();
            $table->string('Calendario', 45);
            $table->date('Fecha')->nullable();
            $table->integer('EsHabil')->default('1')->nullable();
            $table->integer('EsFestivo')->default('0')->nullable();
            $table->integer('DiaAno')->default('0')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_calendarios');
    }
};
