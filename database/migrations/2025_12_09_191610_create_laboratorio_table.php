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
            $table->foreignId('cliente_empresa_id')->constrained('cliente_empresa');
            $table->foreignId('empresa_id')->constrained('empresa');
            $table->string('nombre');
            $table->string('responsable')->nullable();
            $table->string('registro_senasag')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();
            $table->string('categoria')->nullable();
            $table->boolean('estado')->default('1');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratorio');
    }
};
