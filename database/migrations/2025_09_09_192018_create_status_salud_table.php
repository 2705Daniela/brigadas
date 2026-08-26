<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_salud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brigadista_id')->constrained()->onDelete('cascade');

            // Parte 1: enfermedades
            $table->string('cardiovascular')->nullable();
            $table->text('cardiovascular_detalle')->nullable();
            $table->string('hipertension')->nullable();
            $table->text('hipertension_detalle')->nullable();
            $table->string('diabetes')->nullable();
            $table->text('diabetes_detalle')->nullable();
            $table->string('epilepsia')->nullable();
            $table->text('epilepsia_detalle')->nullable();
            $table->string('trastornos_mentales')->nullable();
            $table->text('trastornos_mentales_detalle')->nullable();
            $table->string('trastornos_visuales')->nullable();
            $table->text('trastornos_visuales_detalle')->nullable();
            $table->string('ets')->nullable();
            $table->text('ets_detalle')->nullable();
            $table->string('hemorragia_interna')->nullable();
            $table->text('hemorragia_interna_detalle')->nullable();
            $table->string('hepatitis')->nullable();
            $table->text('hepatitis_detalle')->nullable();
            $table->string('otra')->nullable();
            $table->text('otra_detalle')->nullable();

            // Parte 2: preguntas
            $table->string('operacion')->nullable();
            $table->text('operacion_detalle')->nullable();
            $table->string('examenes')->nullable();
            $table->text('examenes_detalle')->nullable();
            $table->string('medicamento_actual')->nullable();
            $table->text('medicamento_actual_detalle')->nullable();
            $table->string('embarazo')->nullable();
            $table->text('embarazo_detalle')->nullable();
            $table->string('alergias')->nullable();
            $table->text('alergias_detalle')->nullable();
            $table->string('tratamiento')->nullable();
            $table->text('tratamiento_detalle')->nullable();
            $table->string('grupo_sanguineo')->nullable();
            $table->text('informacion_adicional')->nullable();
            $table->date('fecha_status')->nullable();
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_salud');
    }
};
