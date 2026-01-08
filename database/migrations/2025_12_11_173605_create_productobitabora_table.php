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
        Schema::create('producto_bitacora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Quién hizo el cambio
            $table->string('evento'); // Registro, Cambio de Estado, Finalización
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_bitacora');
    }
};
