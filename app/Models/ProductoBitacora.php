<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoBitacora extends Model
{
    protected $table = 'producto_bitacora';
    protected $fillable = ['producto_id', 'user_id', 'evento', 'estado_anterior', 'estado_nuevo', 'observacion'];

    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }
}