<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable(); // admin que hace la acción
            $table->unsignedBigInteger('brigadista_id')->nullable(); // sobre qué brigadista
            $table->string('accion'); // Validó, Rechazó, Eliminó PDF, etc.
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('brigadista_id')->references('id')->on('brigadistas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
