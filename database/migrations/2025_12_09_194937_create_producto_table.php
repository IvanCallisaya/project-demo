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
            $table->integer('estado')->default(1);
            $table->string('id_presolicitud');
            $table->dateTime('fecha_solicitud')->nullable();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursal');
            $table->foreignId('cliente_empresa_id')->nullable()->constrained('cliente_empresa');
            $table->string('tramite');
            $table->foreignId('laboratorio_titular_id')->nullable()->constrained('laboratorio');
            $table->foreignId('laboratorio_produccion_id')->nullable()->constrained(table: 'laboratorio');
            $table->dateTime('fecha_inicio')->nullable();
            $table->string('codigo_tramite')->nullable();;
            $table->string('nombre')->nullable();
            $table->foreignId('subcategoria_id')->nullable()->constrained('subcategoria');
            $table->string('codigo')->nullable();
            $table->date('fecha_registro')->nullable();
            $table->date('fecha_vencimiento')->nullable();
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
