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
}