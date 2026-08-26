<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brigadista_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('brigada_id')->constrained()->onDelete('cascade'); 
             $table->foreignId('supervisor_id')->nullable()->constrained('supervisores')->onDelete('set null');
            $table->string('municipio_asignado')->nullable();
            $table->string('estado_asignado')->nullable();
            $table->string('proyecto_asignado')->nullable();
            $table->boolean('publicado')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaciones');
    }
};
