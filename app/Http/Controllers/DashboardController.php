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




        $plazos = Documento::with(['producto.clienteEmpresa']) // Carga ansiosa de relaciones
            ->select('nombre', 'fecha_plazo_entrega', 'id', 'url', 'producto_id')
            ->whereNotNull('fecha_plazo_entrega')
            ->get();
        Log::info("Plazos encontrados: " . $plazos->toJson());
        $documentoEventos = $plazos->map(function ($doc) {
            return [
                'title' => 'Plazo: ' . $doc->nombre,
                'start' => $doc->fecha_plazo_entrega,
                'color' => $doc->producto->estado_color, // Rojo
                'extendedProps' => [
                    'tipo'        => 'Documento',
                    'descripcion' => 'Plazo límite de entrega del documento.',
                    'estado'     => $doc->producto->estado_nombre ?? 'Sin Estado',
                    'producto'    => $doc->producto->nombre ?? 'Sin producto',
                    'empresa'     => $doc->producto->clienteEmpresa->nombre ?? 'Sin empresa',
                    'codigo_prod' => $doc->producto->codigo_tramite
                ],
                'url'    => $doc->url,
            ];
        });

        $productos = Producto::with(['clienteEmpresa'])->whereIn('estado', [Producto::SOLICITADO])->get();

        $eventos = $productos->map(function ($p) {
            $fechaEntrega = \Carbon\Carbon::parse($p->fecha_solicitud)->addDay()->format('Y-m-d');
            return [
                'title'           => $p->id_presolicitud . ' ' . $p->tramite,
                'start'           => $fechaEntrega,
                'backgroundColor' => $p->estado_color,
                'borderColor'     => $p->estado_color,
                'allDay'          => true,
                'extendedProps' => [
                    'tipo' => 'Pre-solicitud',
                    'estado' => $p->estado_nombre,
                    'tramite' => $p->tramite,
                    'producto'    => $p->nombre ?? 'Pre-solicitud no aprobada',
                    'empresa'     => $p->clienteEmpresa->nombre ?? 'Sin empresa',
                    'codigo_prod' => $p->id_presolicitud
                ],
                'url'             => route('producto.edit', $p->id),
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
