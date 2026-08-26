<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brigadistas', function (Blueprint $table) {
            $table->id();
            
            // Datos del brigadista
            $table->string('numero_boleta')->unique();
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('nombre');
            $table->string('curp', 18)->unique();
            $table->string('unidad_academica');
            $table->string('programa_academico');
            $table->string('especialidad')->nullable();
            $table->string('situacion_academica')->nullable();
            $table->string('institucion_ss');
            $table->string('nss');
            $table->string('telefono');
            $table->string('correo');
            $table->string('password');
            $table->string('password_real')->nullable()->after('password');
            $table->string('calle');
            $table->string('no_int')->nullable();
            $table->string('no_ext');
            $table->string('colonia');
            $table->string('municipio');
            $table->string('estado');
            $table->string('cp', 5);
            $table->string('clabe', 18);
            $table->string('banco');
            $table->string('genero');
            $table->integer('edad');
            $table->string('foto')->nullable();

            // Contacto de emergencia
            $table->string('contacto_nombre');
            $table->string('contacto_parentesco');
            $table->string('calle_contacto');
            $table->string('no_int_contacto')->nullable();
            $table->string('no_ext_contacto');
            $table->string('colonia_contacto');
            $table->string('municipio_contacto');
            $table->string('estado_contacto');
            $table->string('cp_contacto', 5);
            $table->string('telefono_contacto');

            // Firma y folio
            $table->string('firma')->nullable();
             $table->string('tipo_firma')->nullable();

            // Status de salud
            $table->boolean('status_completado')->default(false);

            // Relación con usuario (opcional)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brigadistas');
    }
};
