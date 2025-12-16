<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use MultiTenantScope;
    protected $table = 'unidad_medida';
    protected $fillable = [
        'nombre',
        'simbolo',
        'descripcion',
        'empresa_id',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
