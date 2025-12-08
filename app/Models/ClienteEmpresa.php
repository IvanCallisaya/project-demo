<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteEmpresa extends Model
{
    protected $table = 'cliente_empresa';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'empresa_id',
        'imagen',
    ];

    public function contactos()
    {
        return $this->hasMany(ContactoCliente::class, 'cliente_empresa_id');
    }
}
