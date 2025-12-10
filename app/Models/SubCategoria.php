<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategoria';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'codigo',
    ];

    // Relación MUCHOS a UNO: Una SubClase pertenece a una Clase
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}