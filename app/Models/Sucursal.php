<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use MultiTenantScope;
    protected $table = 'sucursal';
    protected $fillable = [
        'cliente_empresa_id',
        'nombre',
        'direccion',
        'nombre_contacto_principal',
        'email_principal',
        'telefono_principal',
        'url_carpeta_drive',
        'empresa_id',
    ];

    public function productos()
    {
        return $this->hasMany(related: Producto::class, foreignKey: 'sucursal_id');
    }
    public function clienteEmpresa()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
}
