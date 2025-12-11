<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria; // 🛑 NUEVA LÍNEA: Importar el modelo Subcategoria
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Usar el alias para Rule

class ProductoController extends Controller
{
    public function index(Request $r)
    {
        // ... (El método index se mantiene igual)
        $perPage = (int) $r->query('per_page', 10);
        $q = $r->query('q');
        $query = Producto::query();

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%");
        }

        $productos = $query->orderBy('nombre')->paginate($perPage)->withQueryString();
        $productos->load('subcategoria'); 
        return view('producto.index', compact('productos'));
    }

    public function create()
    {
        $subcategorias = Subcategoria::orderBy('nombre')->get();
        return view('producto.create', compact('subcategorias'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'codigo' => 'nullable|string|max:50|unique:producto,codigo',
            'nombre' => 'required|string|max:191',
            // 🛑 CAMBIO 2: Añadir validación para subcategoria_id 🛑
            'subcategoria_id' => 'required|exists:subcategoria,id',
            'categoria' => 'nullable|string',
            'unidad_medida' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]);

        $producto = Producto::create($data);

        // ... (resto del método store)

        // if request includes laboratorio_id -> attach
        if ($r->filled('laboratorio_id')) {
            $producto->laboratorios()->attach($r->laboratorio_id);
            return redirect()->route('laboratorios.show', $r->laboratorio_id)
                ->with('success', 'Producto creado y asignado.');
        }

        return redirect()->route('producto.index')->with('success', 'Producto creado.');
    }


    public function edit(Producto $producto)
    {
        $subcategorias = Subcategoria::orderBy('nombre')->get();
        return view('producto.edit', compact('producto', 'subcategorias'));
    }

    public function update(Request $r, Producto $producto)
    {
        $data = $r->validate([
            'codigo' => ['nullable', 'string', 'max:50', Rule::unique('producto', 'codigo')->ignore($producto->id)],
            'nombre' => 'required|string|max:191',
            'subcategoria_id' => 'required|exists:subcategoria,id',
            'unidad_medida' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]);
        
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