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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial'); // Nombre corto o de fantasía
            $table->string('razon_social')->nullable(); // Nombre legal (Opcional, si no siempre aplica)
            $table->string('nit')->unique(); // Número de Identificación Tributaria, debe ser único
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps(); // Crea las columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
