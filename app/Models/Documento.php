<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use MultiTenantScope;
    protected $table = 'documento';
    protected $fillable = [
        'producto_id',
        'nombre',
        'url',
        'fecha_plazo_entrega',
        'fecha_recojo',
        'empresa_id',
    ];

    public function laboratorioProducto()
    {
        return $this->belongsTo(LaboratorioProducto::class, 'laboratorio_producto_id');
    }
}
