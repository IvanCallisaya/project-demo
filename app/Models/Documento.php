<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documento';
    protected $fillable = [
        'laboratorio_producto_id',
        'nombre',
        'url',
        'fecha_plazo_entrega',
        'fecha_recojo',
    ];

    public function laboratorioProducto()
    {
        return $this->belongsTo(LaboratorioProducto::class, 'laboratorio_producto_id');
    }
}
