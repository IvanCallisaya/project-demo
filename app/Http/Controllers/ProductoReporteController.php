<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosReporteExport;
use App\Models\Documento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProductoReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->aplicarFiltros($request);

        $productos = $query->paginate(20)->withQueryString();
        $categorias = Categoria::all();

        return view('reporte.producto.index', compact('productos', 'categorias'));
    }



    private function aplicarFiltros(Request $request)
    {
        // 1. Iniciamos la consulta con Eager Loading y filtramos SOLO Finalizados
        $query = Producto::with(['subcategoria.categoria', 'clienteEmpresa', 'laboratorioTitular', 'laboratorioProduccion'])
            ->where('estado', Producto::FINALIZADO);

        // 2. Búsqueda por Nombre Producto o Registro Nro
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                    ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
            });
        }

        // 3. Filtro por Categoría
        if ($request->filled('categoria_id')) {
            $query->whereHas('subcategoria.categoria', function ($q) use ($request) {
                $q->where('id', $request->categoria_id);
            });
        }

        // 4. Filtro por Subcategoría (Nuevo criterio)
        if ($request->filled('subcategoria_id')) {
            $query->where('subcategoria_id', $request->subcategoria_id);
        }

        // 5. Filtro por Razón Social (Corregido: nombre de columna y variable)
        if ($request->filled('razon_social')) {
            $query->whereHas('clienteEmpresa', function ($q) use ($request) {
                // Asegúrate que la columna en la tabla cliente_empresa sea 'nombre' o 'nombre_empresa'
                $q->where('nombre', 'like', '%' . $request->razon_social . '%');
            });
        }

        // 6. Filtro por Laboratorio o País
        if ($request->filled('laboratorio')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('laboratorioTitular', function ($sq) use ($request) {
                    $sq->where('nombre', 'like', '%' . $request->laboratorio . '%')
                        ->orWhere('pais', 'like', '%' . $request->laboratorio . '%');
                })->orWhereHas('laboratorioProduccion', function ($sq) use ($request) {
                    $sq->where('nombre', 'like', '%' . $request->laboratorio . '%')
                        ->orWhere('pais', 'like', '%' . $request->laboratorio . '%');
                });
            });
        }

        return $query;
    }

    public function indexPresolicitud(Request $request)
    {
        $query = $this->aplicarFiltrosPresolicitud($request);

        // Paginar resultados manteniendo los filtros en la URL
        $preSolicitudes = $query->latest()->paginate(20)->withQueryString();;
        

        
        $estados = Producto::getEstadosPreSolicitud();
        Log::info(json_encode($estados));
        

        return view('reporte.presolicitud.index', compact('preSolicitudes', 'estados'));
    }

    private function aplicarFiltrosPresolicitud(Request $request)
    {
        // 1. Eager Loading para optimizar consultas SQL
        $estados = Producto::getEstadosPreSolicitud();
        $query = Producto::with(['clienteEmpresa', 'sucursal', 'bitacoras.usuario'])
        ->whereIn('estado', array_keys($estados));
        

        // 2. Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 3. Filtro por Rango de Fechas (Fecha de Solicitud con precisión de minutos)
        // Se espera fecha_desde y fecha_hasta en formato 'Y-m-d H:i'
        if ($request->filled('fecha_desde')) {
            $query->where('created_at', '>=', Carbon::parse($request->fecha_desde));
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('created_at', '<=', Carbon::parse($request->fecha_hasta));
        }

        // 4. Búsqueda General (Nro Solicitud, Nombre Cliente o Sucursal)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('id', 'like', "%$buscar%") // ID o Correlativo
                    ->orWhereHas('clienteEmpresa', function ($sq) use ($buscar) {
                        $sq->where('nombre', 'like', "%$buscar%");
                    })
                    ->orWhereHas('sucursal', function ($sq) use ($buscar) {
                        $sq->where('nombre', 'like', "%$buscar%");
                    });
            });
        }

        return $query;
    }
    public function indexDocumento(Request $request)
    {
        $query = Documento::with(['producto.clienteEmpresa', 'producto.sucursal']);

        // 1. Filtro de Búsqueda (Nombre del documento o Producto)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                  ->orWhereHas('producto', function($sq) use ($buscar) {
                      $sq->where('nombre', 'like', "%$buscar%");
                  });
            });
        }

        // 2. Filtro de Fechas Dinámico
        if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
            // El usuario elige qué columna filtrar: 'fecha_plazo_entrega' o 'fecha_recojo'
            $columnaFecha = $request->tipo_fecha === 'recojo' ? 'fecha_recojo' : 'fecha_plazo_entrega';
            
            $query->whereBetween($columnaFecha, [
                $request->fecha_desde, 
                $request->fecha_hasta
            ]);
        }

        $documentos = $query->latest()->paginate(20)->withQueryString();

        return view('reporte.documento.index', compact('documentos'));
    }
}
