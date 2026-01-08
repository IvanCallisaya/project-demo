<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\ClienteEmpresa;
use App\Models\Laboratorio;
use App\Models\Producto;
use App\Models\SubCategoria;
use App\Models\Sucursal;
use App\Models\UnidadMedida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{

    public function preSolicitudIndex()
    {
        $perPage = request()->input('per_page', 10);
        // Solo mostramos los que están en estados de pre-solicitud (Solicitado, Aprobado, Rechazado)
        $productos = Producto::whereIn('estado', [Producto::SOLICITADO, Producto::APROBADO, Producto::RECHAZADO])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return view('presolicitud.index', compact('productos', 'perPage'));
    }

    public function cambiarEstado(Request $request, $id)
    {

        $producto = Producto::findOrFail($id);
        Log::info("Cambiando estado del producto ID: {}" . $request->nuevo_estado);
        $request->validate([
            'nuevo_estado' => 'required|integer|in:' . implode(',', [
                Producto::SOLICITADO,
                Producto::APROBADO,
                Producto::RECHAZADO,
                Producto::OBSERVADO,
                Producto::PENDIENTE,
                Producto::EN_CURSO,
                Producto::FINALIZADO
            ]),
        ]);

        $producto->estado = $request->nuevo_estado;
        $producto->save();

        return back()->with('success', 'Estado actualizado a: ' . $producto->estado_nombre);
    }


    public function preSolicitudCreate()
    {
        $sucursales = Sucursal::where('empresa_id', auth()->user()->empresa_id)->get()->load('clienteEmpresa');
        $clientes = ClienteEmpresa::where('empresa_id', auth()->user()->empresa_id)->get();
        $opcionesTramite = Producto::opcionesTramite();
        return view('presolicitud.create', compact('sucursales', 'clientes', 'opcionesTramite'));
    }

    public function preSolicitudStore(Request $request)
    {
        $validated = $request->validate([
            'id_presolicitud' => 'required|string|max:255',
            'fecha_solicitud' => 'required|date',
            'tramite'         => 'required|string|max:255',
            'sucursal_id'     => 'nullable|exists:sucursal,id',
            'cliente_empresa_id' => 'nullable|exists:cliente_empresa,id',
        ]);

        // Forzamos el estado SOLICITADO al crear
        $validated['fecha_solicitud'] = \Carbon\Carbon::parse($request->fecha_solicitud)->format('Y-m-d H:i:s');
        $validated['estado'] = Producto::SOLICITADO;

        Producto::create($validated);

        return redirect()->route('presolicitud.index')
            ->with('success', 'Pre-solicitud registrada con éxito.');
    }

    public function index(Request $r)
    {
        // ... (El método index se mantiene igual)
        $perPage = (int) $r->query('per_page', 10);
        $q = $r->query('q');
        $productos = Producto::whereIn('estado', [Producto::OBSERVADO, Producto::PENDIENTE, Producto::EN_CURSO, Producto::FINALIZADO]);
        $query = $productos->newQuery();

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%");
        }

        $productos = $query->orderBy('nombre')->paginate($perPage)->withQueryString();
        $productos->load('subcategoria');
        $productos->load('clienteEmpresa');
        Log::info($productos);
        return view('producto.index', compact('productos'));
    }

    public function create()
    {
        $productos = Producto::whereIn('estado', [Producto::APROBADO])->get();
        $categorias = Categoria::all(); // Asegúrate de importar el modelo
        $subcategorias = SubCategoria::all();
        $laboratorios = Laboratorio::all();
        Log::info(json_encode($productos));
        return view('producto.create', compact('productos', 'categorias', 'subcategorias', 'laboratorios'));
    }

    public function store(Request $r)
    {
        // 1. Validar primero
        $data = $r->validate([
            'id_presolicitud'           => 'required|exists:producto,id',
            'codigo'                    => 'nullable|string|max:50|unique:producto,codigo,' . $r->id_presolicitud,
            'nombre'                    => 'required|string|max:191',
            'subcategoria_id'           => 'required|exists:subcategoria,id',
            'laboratorio_titular_id'    => 'nullable|exists:laboratorio,id',
            'laboratorio_produccion_id' => 'nullable|exists:laboratorio,id',
            'codigo_tramite'            => 'nullable|string|max:100',
        ]);

        // 2. Buscar la pre-solicitud existente
        $producto = Producto::findOrFail($r->id_presolicitud);

        // 3. Preparar datos adicionales
        $data['fecha_inicio'] = \Carbon\Carbon::parse($r->fecha_inicio)->format('Y-m-d H:i:s');
        $data['estado'] = Producto::EN_CURSO; // Cambiamos el estado aquí

        // 4. Actualizar en lugar de Create
        $producto->update($data);

        return redirect()->route('producto.index')->with('success', 'Trámite iniciado y producto actualizado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $productos = Producto::whereIn('estado', [Producto::APROBADO])->get();
        $categorias = Categoria::all(); // Asegúrate de importar el modelo
        $subcategorias = SubCategoria::all();
        $laboratorios = Laboratorio::all();
        return view('producto.edit', compact('producto', 'productos', 'categorias', 'subcategorias', 'laboratorios'));
    }

    public function update(Request $r, Producto $producto)
    {
        $data = $r->validate([
            'codigo'                    => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('producto', 'codigo')->ignore($producto->id),
            ],
            'nombre'                    => 'required|string|max:191',
            'subcategoria_id'           => 'required|exists:subcategoria,id',
            'laboratorio_titular_id'    => 'nullable|exists:laboratorio,id',
            'laboratorio_produccion_id' => 'nullable|exists:laboratorio,id',
            'codigo_tramite'            => 'nullable|string|max:100',
        ]);
        Log::info("Actualizando producto ID: {}" . $producto->id);
        $producto->update($data);
        return redirect()->route('producto.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        try {
            // Intenta eliminar el producto
            $producto->delete();

            return back()->with('success', 'Producto eliminado correctamente.');
        } catch (QueryException $e) {
            // Verifica si el código de error es el de restricción de llave foránea de MySQL (1451)
            if ($e->getCode() === '23000') {
                // Mensaje personalizado para el usuario
                return back()->with('error', '🚫 **Error:** No se puede eliminar el producto porque está asignado a uno o más laboratorios.');
            }

            // Si es otro tipo de error de consulta, puedes registrarlo o lanzar la excepción.
            // En este caso, simplemente retornamos un mensaje de error genérico.
            return back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el producto.');
        }
    }
}
