<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Model;

class ContactoCliente extends Model
{
    use MultiTenantScope;
    protected $table = 'contactos_cliente';

    protected $fillable = [
        'cliente_empresa_id',
        'nombre',
        'email',
        'telefono',
        'empresa_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
}
