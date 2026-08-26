<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brigadas', function (Blueprint $table) {
            $table->id();
            
            // Tipo de brigada
            $table->enum('brigada', ['Primavera', 'Verano', 'Otoño', 'Emergente', 'Extraordinaria']);
            
            // Año de la brigada
            $table->year('año');
            
            // Periodo de registro
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            
            // Estado activo/inactivo
            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brigadas');
    }
};
