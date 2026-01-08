<?php

namespace App\Models;

use App\Models\Traits\MultiTenantScope;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use MultiTenantScope;
    protected $table = 'producto';

    protected $fillable = [
        'estado',
        'id_presolicitud',
        'fecha_solicitud',
        'sucursal_id',
        'cliente_empresa_id',
        'tramite',
        'laboratorio_titular_id',
        'laboratorio_produccion_id',
        'fecha_inicio',
        'codigo_tramite',
        'nombre',
        'subcategoria_id',
        'codigo',
        'empresa_id'
    ];

    // ESTADOS PRE-SOLICITUD
    const SOLICITADO = 1;
    const APROBADO   = 2;
    const RECHAZADO  = 3;

    // ESTADOS TRÁMITE (Post-Aprobación)
    const OBSERVADO  = 4;
    const PENDIENTE  = 5;
    const EN_CURSO   = 6;
    const FINALIZADO = 7;

    public function getEstadoNombreAttribute()
    {
        return match ($this->estado) {
            self::SOLICITADO => 'Solicitado',
            self::APROBADO   => 'Aprobado',
            self::RECHAZADO  => 'Rechazado',
            self::OBSERVADO  => 'Observado',
            self::PENDIENTE  => 'Pendiente',
            self::EN_CURSO   => 'En Curso',
            self::FINALIZADO => 'Finalizado',
            default          => 'Desconocido',
        };
    }

    public function getEstadoColorAttribute()
    {
        return match ($this->estado) {
            self::SOLICITADO => '#17a2b8', // Cyan
            self::APROBADO   => '#28a745', // Verde
            self::RECHAZADO  => '#dc3545', // Rojo
            self::OBSERVADO  => '#ff851b', // Naranja
            self::PENDIENTE  => '#6c757d', // Gris
            self::EN_CURSO   => '#007bff', // Azul
            self::FINALIZADO => '#3d9970', // Oliva
            default          => '#333',
        };
    }

    public static function opcionesTramite()
{
    return [
        'Modificación de registro sanitario, con inspección (IA/SA)',
        'Modificación de registro sanitario (IA/SA)',
        'Registro sanitario de empresas veterinarias Importadoras Comercializadoras.',
        'Registro zoosanitario de productos de uso veterinario (Biológico)',
        'Registro zoosanitario de productos de uso veterinario (Alimento Balanceado)',
        'Registro zoosanitario de productos de uso veterinario (Farmacológico)',
        'Registro zoosanitario de productos de uso veterinario (Insumos para la producción pecuaria)',
        'Registro zoosanitario de productos de uso veterinario (homeopáticos y otros)',
    ];
}

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
    public function subcategoria()
    {
        return $this->belongsTo(SubCategoria::class, 'subcategoria_id');
    }
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }
    public function clienteEmpresa()
    {
        return $this->belongsTo(ClienteEmpresa::class, 'cliente_empresa_id');
    }
}
