<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_empresa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('nombre_contacto_principal')->nullable();
            $table->string('email_principal')->nullable();
            $table->string('telefono_principal')->nullable();
            $table->foreignId('empresa_id')->nullable()->constrained('empresa');
            $table->string('imagen')->nullable();
            $table->string('url_carpeta_drive')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_empresa');
    }
};
