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
        $table->integer('stock')->nullable();
        $table->string('lote')->nullable();
        $table->date('costo_analisis')->nullable();
        $table->date('tiempo_entrega')->nullable();
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
