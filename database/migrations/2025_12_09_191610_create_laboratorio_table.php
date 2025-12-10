<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laboratorio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_empresa_id')->nullable()->constrained('cliente_empresa');
            $table->string('nombre');
            $table->string('responsable')->nullable(); // responsable técnico
            $table->string('registro_senasag')->nullable(); // nro de registro sanitario
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();

            $table->string('categoria')->nullable(); // Ej: fertilizantes, agroquímicos, alimentos balanceados
            $table->string('estado')->default('Activo'); // Activo / Inactivo

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratorio');
    }
};
