<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteEmpresa extends Model
{
    protected $table = 'cliente_empresa';

    protected $fillable = [
        'nombre',
        'direccion',
        'nombre_contacto_principal',
        'email_principal',
        'telefono_principal',
        'empresa_id',
        'imagen',
        'url_carpeta_drive',
    ];

    public function contactos()
    {
        return $this->hasMany(ContactoCliente::class, 'cliente_empresa_id');
    }
    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class, 'cliente_empresa_id');
    }
}
