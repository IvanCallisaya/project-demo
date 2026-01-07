<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('id_presolicitud');
            $table->date('fecha_solicitud');
            $table->string('tramite');
            $table->foreignId('laboratorio_titular_id')->nullable()->constrained('laboratorio');
            $table->foreignId('laboratorio_produccion_id')->nullable()->constrained(table: 'laboratorio');
            $table->date('fecha_inicio')->nullable();
            $table->string('codigo_tramite')->nullable();;
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursal');
            $table->string('nombre');
            $table->foreignId('subcategoria_id')->nullable()->constrained('subcategoria');
            $table->string('codigo')->nullable(); //solo tiene cuando se finaliza el tramite del producto
            $table->foreignId('empresa_id')->constrained('empresa');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
