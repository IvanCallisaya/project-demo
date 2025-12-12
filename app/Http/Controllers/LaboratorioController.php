<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\ClienteEmpresa;
use App\Models\LaboratorioProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaboratorioController extends Controller
{
    // index with search + per_page config
    public function index(Request $r)
    {
        $perPage = (int) ($r->query('per_page', 10));
        $q = $r->query('q');

        $query = Laboratorio::query()->withCount('productos');

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%");
        }

        $labs = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();


        return view('laboratorio.index', compact('labs', 'perPage'));
    }

    public function create(Request $r, $cliente_empresa_id = 1)
    {

        $clientes = ClienteEmpresa::orderBy('id')->get();
        Log::info("Cliente empresa ID para crear laboratorio: " . $clientes);
        return view('laboratorio.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        Laboratorio::create(
            [
                'cliente_empresa_id' => $request->cliente_empresa_id,
                'nombre' => $request->nombre,
                'responsable' => $request->responsable,
                'registro_senasag' => $request->registro_senasag,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'ciudad' => $request->ciudad,
                'direccion' => $request->direccion,
                'categoria' => $request->categoria,
                'estado' => 1,
                'observaciones' => $request->observaciones,
            ]
        );
        return redirect()->route('laboratorio.index')
            ->with('success', 'Laboratorio creado.');
    }

    public function edit($laboratorio_id)
    {
        $laboratorio = Laboratorio::findOrFail($laboratorio_id);
        $clientes = ClienteEmpresa::orderBy('nombre')->get();
        return view('laboratorio.edit', compact('laboratorio', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $laboratorio->update($request->only([
            'nombre',
            'responsable',
            'registro_senasag',
            'telefono',
            'email',
            'ciudad',
            'direccion',
            'categoria',
            'estado',
            'observaciones'
        ]));
        return redirect()->route('laboratorio.index', $laboratorio->id)->with('success', 'Actualizado.');
    }

    public function show(Laboratorio $laboratorio)
    {
        $laboratorio->load('productos');
        // provide all products for selection (for convenience with search)
        $productos = \App\Models\Producto::orderBy('nombre')->paginate(20);
        return view('laboratorio.show', compact('laboratorio', 'productos'));
    }

    public function destroy(Laboratorio $laboratorio)
    {
        try {
            $laboratorio->delete();
            return redirect()->route('laboratorio.index')
                ->with('success', 'Laboratorio eliminado.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                // Mensaje personalizado para el usuario
                return back()->with('error', '🚫 **Error:** No se puede eliminar el laboratorio porque tiene productos asignados.');
            }

            // Si es otro tipo de error de consulta, puedes registrarlo o lanzar la excepción.
            // En este caso, simplemente retornamos un mensaje de error genérico.
            return back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el laboratorio.');
        }
    }
    // app/Http/Controllers/LaboratorioController.php (o donde esté esta función)

    public function attachProducto(Request $request, Laboratorio $laboratorio)
    {
        // 1. Validación de campos Pivot (Agregamos fecha_entrega)
        $request->validate([
            'costo_analisis' => 'nullable|numeric|min:0',
            'tiempo_entrega_dias' => 'nullable|integer|min:0',
            'fecha_entrega' => 'nullable|date',
        ]);

        $pivotData = [
            'costo_analisis' => $request->costo_analisis,
            'tiempo_entrega_dias' => $request->tiempo_entrega_dias,
            'fecha_entrega' => $request->fecha_entrega,
            'estado' => LaboratorioProducto::ESTADO_INICIADO
        ];

        try {
            $laboratorio->productos()->attach($request->producto_id, $pivotData);

            return redirect()->route('laboratorio.show', $laboratorio)
                ->with('success', 'Producto asignado y datos de inventario guardados correctamente.');
        } catch (\Exception $e) {
            // ... (Manejo de errores)
            Log::error("Error al asignar producto al laboratorio: " . $e->getMessage());
            return redirect()->route('laboratorio.show', $laboratorio)
                ->with('error', 'Error al asignar el producto.');
        }
    }

    public function detachProducto(Laboratorio $laboratorio, LaboratorioProducto $pivotRecord)
    {
        try {
            // Verificamos que el registro pivot pertenezca al laboratorio correcto (Seguridad)
            if ($pivotRecord->laboratorio_id !== $laboratorio->id) {
                return redirect()->back()->with('error', 'Registro de inventario no válido.');
            }

            // Usamos el método delete() del modelo para eliminar la fila específica.
            $pivotRecord->delete();

            return redirect()->route('laboratorio.show', $laboratorio)
                ->with('success', 'Elemento de inventario removido del laboratorio.');
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                // Mensaje personalizado para el usuario
                return back()->with('error', 'strongError: No se puede eliminar el producto del laboratorio porque tiene documentos subidos');
            }

            // Si es otro tipo de error de consulta, puedes registrarlo o lanzar la excepción.
            // En este caso, simplemente retornamos un mensaje de error genérico.
            return back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el producto.');
        }
        // Verificamos que el registro pivot pertenezca al laboratorio correcto (Seguridad)
        if ($pivotRecord->laboratorio_id !== $laboratorio->id) {
            return redirect()->back()->with('error', 'Registro de inventario no válido.');
        }
    }

    // 2. EDITAR PIVOT (Mostrar Formulario)
    // Ahora recibe LaboratorioProducto $pivotRecord
    public function editPivot($laboratorio_id, $pivotRecord)
    {
        // Verificamos que el registro pivot pertenezca al laboratorio correcto
        $laboratorio = Laboratorio::findOrFail($laboratorio_id);
        $pivotRecord = LaboratorioProducto::findOrFail($pivotRecord);
        if ($pivotRecord->laboratorio_id !== $laboratorio->id) {
            return redirect()->back()->with('error', 'Registro de inventario no válido.');
        }

        // El modelo pivot $pivotRecord ya tiene todos los datos (stock, lote, etc.)
        // También necesitamos el modelo Producto para el título y detalles.
        $producto = $pivotRecord->producto;

        // Nota: El nombre de la vista de blade sigue siendo 'laboratorio.edit_pivot'
        return view('laboratorio.edit_pivot', compact('laboratorio', 'producto', 'pivotRecord'));
    }

    // 3. ACTUALIZAR PIVOT (Guardar)
    // Ahora recibe LaboratorioProducto $pivotRecord
    public function updatePivot(Request $request, Laboratorio $laboratorio, LaboratorioProducto $pivotRecord)
    {
        // Verificación de seguridad
        if ($pivotRecord->laboratorio_id !== $laboratorio->id) {
            return redirect()->back()->with('error', 'Registro de inventario no válido.');
        }

        $request->validate([
            'costo_analisis' => 'nullable|decimal:0,2',
            'tiempo_entrega_dias' => 'nullable|integer',
            'fecha_entrega' => 'nullable|date',
        ]);

        $pivotData = [
            'costo_analisis' => $request->costo_analisis,
            'tiempo_entrega_dias' => $request->tiempo_entrega_dias,
            'fecha_entrega' => $request->fecha_entrega,
        ];

        // Usamos el método update() del modelo pivot
        $pivotRecord->update($pivotData);

        return redirect()->route('laboratorio.show', $laboratorio)
            ->with('success', 'Elemento de inventario actualizado correctamente.');
    }
}
