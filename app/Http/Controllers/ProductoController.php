<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\ClienteEmpresa;
use App\Models\Laboratorio;
use App\Models\Producto;
use App\Models\ProductoBitacora;
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
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('presolicitud.index', compact('productos', 'perPage'));
    }

    public function cambiarEstado(Request $request, $id)
    {

        $producto = Producto::findOrFail($id);
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
        $estadoAntiguo = $producto->getOriginal('estado');
        $producto->save();

        ProductoBitacora::create([
            'producto_id' => $producto->id,
            'user_id' => auth()->id(),
            'evento' => 'Cambio de Estado',
            'estado_anterior' => Producto::getNombreEstado($estadoAntiguo),
            'estado_nuevo' => Producto::getNombreEstado($request->nuevo_estado),
            'observacion' => $request->observacion ?? 'Cambio de estado manual.'
        ]);

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

        $producto = Producto::create($validated);
        ProductoBitacora::create([
            'producto_id' => $producto->id,
            'user_id' => auth()->id(),
            'evento' => 'Registro de Pre-solicitud',
            'estado_nuevo' => $producto->getEstadoNombreIdAttribute(Producto::SOLICITADO),
            'observacion' => 'Se registró la pre-solicitud inicial.'
        ]);

        return redirect()->route('presolicitud.index')
            ->with('success', 'Pre-solicitud registrada con éxito.');
    }

    public function index(Request $r)
    {
        $perPage = (int) $r->query('per_page', 10);
        $q = $r->query('q');
        $estado = $r->query('estado'); // Nuevo filtro

        // 1. Iniciamos la consulta con los estados permitidos base
        $query = Producto::whereIn('estado', [
            Producto::OBSERVADO,
            Producto::PENDIENTE,
            Producto::EN_CURSO,
            Producto::FINALIZADO
        ]);

        // 2. Filtro por Estado específico (si el usuario selecciona uno)
        if ($estado) {
            $query->where('estado', $estado);
        }

        // 3. Búsqueda por Nombre, Código o Encargado (ClienteEmpresa)
        if ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('nombre', 'like', "%{$q}%")
                    ->orWhere('codigo', 'like', "%{$q}%")
                    // Buscar en la relación clienteEmpresa
                    ->orWhereHas('clienteEmpresa', function ($relacion) use ($q) {
                        $relacion->where('nombre', 'like', "%{$q}%");
                    });
            });
        }

        $productos = $query->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();

        $productos->load(['subcategoria', 'clienteEmpresa']);

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
            'codigo'                    => 'nullable|string|max:50|unique:producto,codigo,' . $r->id_presolicitud,
            'nombre'                    => 'required|string|max:191',
            'subcategoria_id'           => 'required|exists:subcategoria,id',
            'laboratorio_titular_id'    => 'nullable|exists:laboratorio,id',
            'laboratorio_produccion_id' => 'nullable|exists:laboratorio,id',
            'codigo_tramite'            => 'nullable|string|max:100',
            'fecha_inicio'              => 'required|date',
        ]);

        // 2. Buscar la pre-solicitud existente
        $producto = Producto::findOrFail($r->id_presolicitud);

        // CAPTURA CRÍTICA: Guardamos el nombre del estado ANTES de actualizar
        $nombreEstadoAnterior = $producto->estado_nombre;

        // 3. Preparar datos adicionales
        $data['fecha_inicio'] = \Carbon\Carbon::parse($r->fecha_inicio)->format('Y-m-d H:i:s');
        $data['estado'] = Producto::EN_CURSO;

        // 4. Actualizar
        $producto->update($data);

        // 5. Registrar Bitácora usando los nombres legibles
        ProductoBitacora::create([
            'producto_id'     => $producto->id,
            'user_id'         => auth()->id(),
            'evento'          => 'Inicio de Trámite',
            'estado_anterior' => $nombreEstadoAnterior, // Ejemplo: "Aprobado"
            'estado_nuevo'    => Producto::getNombreEstado(Producto::EN_CURSO), // Ejemplo: "En Curso"
            'observacion'     => 'Se completaron los datos del producto y se inició el trámite.'
        ]);

        return redirect()->route('producto.index')->with('success', 'Trámite iniciado y producto actualizado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $productos = Producto::whereIn('estado', [Producto::APROBADO])->get();
        $categorias = Categoria::all(); // Asegúrate de importar el modelo
        $subcategorias = SubCategoria::all();
        $laboratorios = Laboratorio::all();
        $bitacoras = $producto->load('bitacoras');
        Log::info($producto);

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
        ProductoBitacora::create([
            'producto_id' => $producto->id,
            'user_id' => auth()->id(),
            'evento' => 'Producto Actualizado',
            'estado_nuevo' => $producto->estado,
            'observacion' => 'Los datos del producto fueron actualizados.'
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
