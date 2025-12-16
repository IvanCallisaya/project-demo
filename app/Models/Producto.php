<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use MultiTenantScope;
    protected $table = 'producto';
    protected $fillable = [
        'nombre',
        'codigo',
        'subcategoria_id',
        'unidad_medida_id',
        'descripcion',
        'empresa_id',
    ];

    public function laboratorios()
    {
        return $this->belongsToMany(Laboratorio::class, 'laboratorio_producto')
            ->withPivot(['id', 'costo_analisis','fecha_recepcion', 'tiempo_entrega_dias', 'estado', 'fecha_entrega'])
            ->withTimestamps();
    }
    public function subcategoria()
    {
        return $this->belongsTo(SubCategoria::class);
    }
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
