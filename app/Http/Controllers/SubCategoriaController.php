<?php

namespace App\Http\Controllers;

use App\Models\SubCategoria;
use App\Models\Categoria; // Necesario para el filtro opcional
use Illuminate\Http\Request;

class SubCategoriaController extends Controller
{
    public function index(Request $r)
    {
        // 1. Obtener parámetros de búsqueda y paginación
        $perPage = (int) $r->query('per_page', 10);
        $search = $r->query('search'); // Usamos 'search' como en tu ejemplo
        $categoria_id = $r->query('categoria_id'); // Agregamos filtro por Categoría Principal

        // 2. Iniciar la consulta, precargando la relación 'categoria'
        $query = SubCategoria::with('categoria');

        // 3. Aplicar Filtro de Búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Buscar por nombre de subcategoría o código
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%");
            });

            // Opcional: Buscar también por nombre de la Categoría principal (requiere join)
            $query->orWhereHas('categoria', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        // 4. Aplicar Filtro por Categoría Principal
        if ($categoria_id) {
            $query->where('categoria_id', $categoria_id);
        }

        // 5. Obtener los resultados paginados
        $subcategorias = $query
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        // 6. Obtener listado de Categorías para el filtro desplegable (si es necesario)
        $categorias = Categoria::orderBy('id')->get();

        // Retornar la vista con los datos y los parámetros usados para mantener el estado del formulario
        return view('subcategoria.index', compact('subcategorias', 'search', 'perPage', 'categorias', 'categoria_id'));
    }

    // ... (create y store se mantienen como los definiste)
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('subcategoria.create', compact('categorias'));
    }

    public function store(Request $request)
    {

        $rules = [
            'categoria_selector' => 'required',
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:10',
        ];


        if ($request->categoria_selector === 'new_categoria') {
            // Regla de unicidad para la nueva categoría
            $rules['new_categoria_nombre'] = 'required|string|max:100';
            $rules['new_categoria_codigo'] = 'nullable|string|max:10';
        } else {
            // Reglas para categoría existente
            $rules['categoria_selector'] = 'required|exists:categoria,id';
        }

        $request->validate($rules);

        $categoria_id = null;


        if ($request->categoria_selector === 'new_categoria') {
            $nuevaCategoria = Categoria::create([
                'nombre' => $request->new_categoria_nombre,
                'codigo' => $request->new_categoria_codigo,
            ]);
            $categoria_id = $nuevaCategoria->id;
        } else {
            $categoria_id = $request->categoria_selector;
        }

        SubCategoria::create([
            'categoria_id' => $categoria_id,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
        ]);

        return redirect()->route('subcategoria.index')->with('success', 'Subcategoría y su categoria (si fue creada) guardadas exitosamente.');
    }

    /**
     * Muestra el formulario para editar una subcategoría.
     */
    public function edit($id) // Usando Route Model Binding
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $subcategoria = SubCategoria::findOrFail($id);
        // El objeto $subcategoria ya contiene el ID de la categoría principal
        return view('subcategoria.edit', compact('subcategoria', 'categorias'));
    }

    /**
     * Actualiza la subcategoría existente.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'categoria_selector' => 'required',
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:10',
        ];


        if ($request->categoria_selector === 'new_categoria') {
            // Regla de unicidad para la nueva categoría
            $rules['new_categoria_nombre'] = 'required|string';
            $rules['new_categoria_codigo'] = 'nullable|string|max:10';
        } else {
            // Reglas para categoría existente
            $rules['categoria_selector'] = 'required|exists:categoria,id';
        }

        $request->validate($rules);

        $categoria_id = null;


        if ($request->categoria_selector === 'new_categoria') {
            $nuevaCategoria = Categoria::create([
                'nombre' => $request->new_categoria_nombre,
                'codigo' => $request->new_categoria_codigo,
            ]);
            $categoria_id = $nuevaCategoria->id;
        } else {
            $categoria_id = $request->categoria_selector;
        }

        $rules = [
            // Validar la unicidad excluyendo la subcategoría actual
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:10',
        ];
        $validatedData = $request->validate($rules);
        Log:
        info('Validating update with rules: ', $validatedData);
        $subcategoria = SubCategoria::findOrFail($id);
        $subcategoria->update([
            'categoria_id' => $categoria_id,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
        ]);

        return redirect()->route('subcategoria.index')->with('success', 'Subcategoría actualizada exitosamente.');
    }

    /**
     * Elimina la subcategoría.
     */
    public function destroy(SubCategoria $subcategoria)
    {
        $subcategoria->delete();
        return redirect()->route('subcategoria.index')->with('success', 'Subcategoría eliminada.');
    }
}
