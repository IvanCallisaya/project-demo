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
        Schema::create('laboratorio_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratorio_id')->nullable()->constrained('laboratorio');
            $table->foreignId('producto_id')->nullable()->constrained('producto');
            $table->decimal('costo_analisis', 10, 2)->nullable();
            $table->integer('tiempo_entrega_dias')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_producto');
    }
};
