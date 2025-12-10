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
        'subcategoria_id',
        'unidad_medida',
        'descripcion'
    ];

    public function laboratorios()
    {
        return $this->belongsToMany(Laboratorio::class, 'laboratorio_producto')
                    ->withPivot(['id','costo_analisis','tiempo_entrega_dias','estado'])
                    ->withTimestamps();
    }
    public function subcategoria()
    {
        return $this->belongsTo(SubCategoria::class);
    }
}
