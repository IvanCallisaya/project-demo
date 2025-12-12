<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LaboratorioProducto extends Pivot
{
    // Opcional: Especificar la tabla y el fillable
    protected $table = 'laboratorio_producto'; 
    protected $fillable = [
        'laboratorio_id', 
        'producto_id', 
        'costo_analisis', 
        'tiempo_entrega_dias',
        'fecha_entrega',
        'estado',
    ]; 
    const ESTADO_INACTIVO = 0;
    const ESTADO_INICIADO = 1;
    const ESTADO_EN_PROCESO = 2;

    const ESTADO_COMPLETADO = 3;

    // Si necesitas relaciones:
    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'laboratorio_producto_id');
    }
    public function getEstadoNombreAttribute()
    {
        return match($this->estado) {
            self::ESTADO_INACTIVO => 'Inactivo',
            self::ESTADO_INICIADO => 'Iniciado',
            self::ESTADO_EN_PROCESO => 'En Proceso',
            self::ESTADO_COMPLETADO => 'Completado',
            default => 'Desconocido',
        };
    }
    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_INICIADO => 'success', // Color verde
            self::ESTADO_EN_PROCESO => 'warning', // Color amarillo/naranja
            self::ESTADO_COMPLETADO => 'primary', // Color azul
            self::ESTADO_INACTIVO => 'danger', // Color rojo
            default => 'secondary',
        };
    }
}