<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $fillable = [
        'nombre',
        'codigo',
        'categoria',
        'unidad_medida',
        'descripcion'
    ];

    public function laboratorios()
    {
        return $this->belongsToMany(Laboratorio::class, 'laboratorio_producto')
                    ->withPivot(['stock','lote','costo_analisis','tiempo_entrega'])
                    ->withTimestamps();
    }
}
