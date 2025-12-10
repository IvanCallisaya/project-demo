<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria'; // Por defecto, pero se especifica

    protected $fillable = [
        'nombre',
        'codigo',
    ];

    // Relación UNO a MUCHOS: Una Clase tiene muchas SubClases
    public function subcategorias()
    {
        return $this->hasMany(SubCategoria::class);
    }
}