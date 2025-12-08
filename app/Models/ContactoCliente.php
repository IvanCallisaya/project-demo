<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoCliente extends Model
{
    protected $table = 'contactos_cliente';

    protected $fillable = [
        'cliente_empresa_id',
        'nombre',
        'email',
        'telefono',
    ];

    public function empresa()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
}
