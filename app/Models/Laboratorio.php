<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

    use MultiTenantScope;
    protected $table = 'laboratorio';
    protected $fillable = [
        'cliente_empresa_id',
        'nombre',
        'responsable',
        'registro_senasag',
        'telefono',
        'email',
        'ciudad',
        'direccion',
        'categoria',
        'estado',
        'observaciones',
        'empresa_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'laboratorio_producto')
            ->using(LaboratorioProducto::class) // <-- ¡ESTA ES LA LÍNEA QUE FALTA!
            ->withPivot(['id', 'costo_analisis', 'fecha_recepcion', 'tiempo_entrega_dias', 'estado', 'laboratorio_id', 'producto_id', 'fecha_entrega'])
            ->withTimestamps();
    }
}
