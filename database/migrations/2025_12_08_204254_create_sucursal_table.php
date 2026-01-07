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
        Schema::create('sucursal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_empresa_id')->constrained('cliente_empresa');
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('nombre_contacto_principal')->nullable();
            $table->string('email_principal')->nullable();
            $table->string('telefono_principal')->nullable();
            $table->string('url_carpeta_drive')->nullable();
            $table->foreignId('empresa_id')->constrained('empresa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal');
    }
};
