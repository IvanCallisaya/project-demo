<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

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
        'observaciones'
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
        public function productos()
    {
        return $this->belongsToMany(Producto::class, 'laboratorio_producto')
                    ->withPivot(['stock','lote','costo_analisis','tiempo_entrega'])
                    ->withTimestamps();
    }
}
