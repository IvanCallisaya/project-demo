<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use App\Models\Documento;
use App\Models\LaboratorioProducto; // El modelo pivot
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Estadísticas de Clientes
        $totalClientes = ClienteEmpresa::count();

        $productosPorEstado = LaboratorioProducto::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Mapeo de estados (asumiendo que usas las constantes que definimos antes)
        $productosIniciado = $productosPorEstado[LaboratorioProducto::ESTADO_INICIADO] ?? 0;
        $productosProceso = $productosPorEstado[LaboratorioProducto::ESTADO_EN_PROCESO] ?? 0;
        $productosCompletado = $productosPorEstado[LaboratorioProducto::ESTADO_COMPLETADO] ?? 0;

        // 3. Eventos para el Calendario (Plazos de Documentos)
        $plazos = Documento::select('nombre', 'fecha_plazo_entrega', 'id')
            ->whereNotNull('fecha_plazo_entrega')
            ->get();

        $documentoEventos = $plazos->map(function ($doc) {
            // Se recomienda usar el ID del cliente o el nombre del producto en el título.
            // Para más información, carga ansiosa la relación laboratorioProducto.
            return [
                'title' => 'Plazo: ' . $doc->nombre,
                'start' => $doc->fecha_plazo_entrega,

                'color' => '#dc3545', // Rojo para Plazo de Entrega
            ];
        });

        return view('dashboard', [
            'totalClientes' => $totalClientes,
            'productosIniciado' => $productosIniciado,
            'productosProceso' => $productosProceso,
            'productosCompletado' => $productosCompletado,
            'documentoEventos' => $documentoEventos,
        ]);
    }
}
