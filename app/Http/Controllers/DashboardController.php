<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use App\Models\Documento;
use App\Models\LaboratorioProducto; // El modelo pivot
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Estadísticas de Clientes
        $totalClientes = ClienteEmpresa::count();

        $productosPorEstado = Producto::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Mapeo de estados (asumiendo que usas las constantes que definimos antes)
        $productosSolicitado = $productosPorEstado[Producto::SOLICITADO] ?? 0;
        $productosAprobado = $productosPorEstado[Producto::APROBADO] ?? 0;
        $productosRechazado = $productosPorEstado[Producto::RECHAZADO] ?? 0;
        $productosObservado = $productosPorEstado[Producto::OBSERVADO] ?? 0;
        $productosPendiente = $productosPorEstado[Producto::PENDIENTE] ?? 0;
        $productosEnCurso = $productosPorEstado[Producto::EN_CURSO] ?? 0;
        $productosFinalizado = $productosPorEstado[Producto::FINALIZADO] ?? 0;


    

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
        $productos = Producto::whereIn('estado', [Producto::SOLICITADO])->get();

        $eventos = $productos->map(function ($p) {
            // Calculamos el día después de la solicitud
            $fechaEntrega = \Carbon\Carbon::parse($p->fecha_solicitud)->addDay()->format('Y-m-d');

            return [
                'title'           => $p->id_presolicitud .' '.$p->tramite . ' - ' . $p->estado_nombre,
                'start'           => $fechaEntrega, // Ahora el evento inicia el día después
                'end'             => $fechaEntrega, // Termina el mismo día
                'backgroundColor' => $p->estado_color,
                'borderColor'     => $p->estado_color,
                'allDay'          => true
            ];
        });
        return view('dashboard', [
            'totalClientes' => $totalClientes,
            'documentoEventos' => $documentoEventos,
            'eventos' => $eventos,
            'productosSolicitado' => $productosSolicitado,
            'productosAprobado' => $productosAprobado,
            'productosRechazado' => $productosRechazado,
            'productosObservado' => $productosObservado,
            'productosPendiente' => $productosPendiente,
            'productosEnCurso' => $productosEnCurso,
            'productosFinalizado' => $productosFinalizado,

        ]);
    }
}
