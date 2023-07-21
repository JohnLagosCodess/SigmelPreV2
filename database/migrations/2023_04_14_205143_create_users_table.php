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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('email_contacto')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('tipo_identificacion')->nullable();
            $table->string('nro_identificacion', 100)->nullable();
            $table->text('tipo_colaborador')->nullable();
            $table->text('empresa')->nullable();
            $table->text('cargo')->nullable();
            $table->string('telefono_contacto', 40)->nullable();
            $table->string('password', 100);
            $table->text('id_procesos_usuario')->nullable();;
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
