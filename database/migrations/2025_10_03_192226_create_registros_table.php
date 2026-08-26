<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brigadista_id')->constrained()->onDelete('cascade');
            $table->foreignId('brigada_id')->constrained()->onDelete('cascade');
            $table->string('rol_deseado')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('talla')->nullable();
            $table->string('siss')->nullable();
            $table->string('participacion_anterior')->nullable();
            $table->string('municipio_anterior')->nullable();
            $table->string('status')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
