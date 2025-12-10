<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $r)
    {
        $perPage = (int) $r->query('per_page', 10);
        $q = $r->query('q');
        $query = Producto::query();

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%");
        }

        $productos = $query->orderBy('nombre')->paginate($perPage)->withQueryString();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'codigo' => 'nullable|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:191',
            'categoria' => 'nullable|string',
            'unidad_medida' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]);

        $producto = Producto::create($data);

        // if request includes laboratorio_id -> attach
        if ($r->filled('laboratorio_id')) {
            $producto->laboratorios()->attach($r->laboratorio_id);
            return redirect()->route('laboratorios.show', $r->laboratorio_id)
                ->with('success', 'Producto creado y asignado.');
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $r, Producto $producto)
    {
        $data = $r->validate([
            'codigo' => ['nullable', 'string', 'max:50', \Illuminate\Validation\Rule::unique('productos', 'codigo')->ignore($producto->id)],
            'nombre' => 'required|string|max:191',
            'categoria' => 'nullable|string',
            'unidad_medida' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]);
        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return back()->with('success', 'Producto eliminado.');
    }
}
