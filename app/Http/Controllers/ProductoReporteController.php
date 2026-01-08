<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosReporteExport;

class ProductoReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->aplicarFiltros($request);

        $productos = $query->paginate(20);
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
}
