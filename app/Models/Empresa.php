<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    public $timestamps = false;
    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'nit',
        'direccion',
        'telefono',
    ];
}
