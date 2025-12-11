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
        Schema::create('documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratorio_producto_id')->nullable()->constrained('laboratorio_producto');
            $table->string('nombre');
            $table->string('url');
            $table->date('fecha_plazo_entrega')->nullable();
            $table->date('fecha_recojo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento');
    }
};
