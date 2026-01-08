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
        'pais',
        'email',
        'telefono',
        'empresa_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }

}
