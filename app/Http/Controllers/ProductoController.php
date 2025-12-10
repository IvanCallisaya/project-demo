<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria; // 🛑 NUEVA LÍNEA: Importar el modelo Subcategoria
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
        // 🛑 CAMBIO 1: Pasar todas las subcategorías a la vista 🛑
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
        $producto->delete();
        return back()->with('success', 'Producto eliminado.');
    }
}