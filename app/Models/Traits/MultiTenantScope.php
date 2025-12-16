<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait MultiTenantScope
{
    /**
     * Aplica el Scope Global al modelo.
     */
    protected static function booted()
    {
        // =======================================================
        // 1. SCOPE GLOBAL (FILTRADO DE LECTURA: SELECT, UPDATE, DELETE)
        // =======================================================

        // Obtener el ID de la empresa del usuario autenticado.
        $empresaId = Auth::check() ? Auth::user()->empresa_id : null;

        // Si hay un usuario autenticado y tiene una empresa asignada...
        if ($empresaId) {
            static::addGlobalScope('empresa', function (Builder $builder) use ($empresaId) {
                $tableName = $builder->getModel()->getTable();
                // Filtra SELECTs y asegura que solo se puedan actualizar/eliminar
                // registros que pertenecen a la empresa actual.
                $builder->where("{$tableName}.empresa_id", $empresaId);
            });
        }

        // =======================================================
        // 2. EVENTO CREATING (ASIGNACIÓN DURANTE ESCRITURA: INSERT)
        // =======================================================

        // Solo si hay un usuario autenticado, asignamos la empresa_id automáticamente.
        if (Auth::check()) {
            static::creating(function ($model) {
                // Asigna la empresa_id al modelo antes de que se ejecute el INSERT.
                // Esto soluciona el error de 'empresa_id cannot be null' al hacer STORE.
                $model->empresa_id = Auth::user()->empresa_id;
            });
        }
    }
}
