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
        $table->string('nombre');
        $table->foreignId('subcategoria_id')->nullable()->constrained('subcategoria');
        $table->string('codigo')->nullable();
        $table->string('unidad_medida')->nullable();
        $table->string('descripcion')->nullable();
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
