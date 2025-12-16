<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ClienteEmpresaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $empresas = ClienteEmpresa::with('contactos')
            ->when($search, function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('telefono_principal', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);
        Log::info("Empresas encontradas: " . $empresas->toJson());
        return view('cliente_empresa.index', compact('empresas', 'search', 'perPage'));
    }
    public function create()
    {
        return view('cliente_empresa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'imagen' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // SUBIR IMAGEN
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('uploads/clientes', 'public');
        }

        // Crear empresa
        $empresa = ClienteEmpresa::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'nombre_contacto_principal' => $request->nombre_contacto_principal,
            'email_principal' => $request->email_principal,
            'telefono_principal' => $request->telefono_principal,
            'imagen' => $rutaImagen,
        ]);

        // Contactos
        if ($request->contactos) {
            foreach ($request->contactos as $c) {
                if (!empty($c['nombre'])) {
                    $empresa->contactos()->create($c);
                }
            }
        }

        return redirect()->route('cliente_empresa.index')
            ->with('success', 'Cliente empresa creado correctamente.');
    }

    public function show($id)
    {
        $clienteEmpresa = ClienteEmpresa::findOrFail($id);
        $clienteEmpresa->load('contactos');
        $currentView = 'resumen';
        return view('cliente_empresa.show', compact('clienteEmpresa', 'currentView'));
    }
    public function edit($id)
    {
        $cliente_empresa = ClienteEmpresa::with('contactos')->findOrFail($id);
        Log::info($cliente_empresa->contactos->toArray());
        return view('cliente_empresa.edit', compact('cliente_empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = ClienteEmpresa::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'imagen' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // SUBIR UNA NUEVA IMAGEN SI SE CAMBIA
        if ($request->hasFile('imagen')) {
            $rutaNueva = $request->file('imagen')->store('uploads/clientes', 'public');
            $empresa->imagen = $rutaNueva;
        }

        $empresa->update($request->only(['nombre', 'direccion', 'telefono', 'nombre_contacto_principal', 'email_principal', 'telefono_principal']));

        // Actualizar contactos
        $empresa->contactos()->delete();

        if ($request->contactos) {
            foreach ($request->contactos as $c) {
                if (!empty($c['nombre'])) {
                    $empresa->contactos()->create($c);
                }
            }
        }

        return redirect()->route('cliente_empresa.index')
            ->with('success', 'Actualizado correctamente.');
    }

    public function destroy($id)
    {
        ClienteEmpresa::findOrFail($id)->delete();
        return back()->with('success', 'Eliminado correctamente.');
    }


    public function laboratoriosIndex(Request $request, ClienteEmpresa $clienteEmpresa)
    {
        $laboratoriosQuery = $clienteEmpresa->laboratorios();

        if ($q = $request->input('q')) {
            $laboratoriosQuery->where('nombre', 'like', '%' . $q . '%');
        }

        $perPage = $request->input('per_page', 10);

        $laboratoriosPaginados = $laboratoriosQuery
            ->withCount('productos')
            ->paginate($perPage)
            ->withQueryString();

        $currentView = 'laboratorios';

        return view('cliente_empresa.show', [
            'clienteEmpresa' => $clienteEmpresa,
            'currentView' => $currentView,
            'labs' => $laboratoriosPaginados,
        ]);
    }
    // app/Http/Controllers/ClienteEmpresaController.php

    public function documentosIndex(Request $request, ClienteEmpresa $clienteEmpresa)
    {
        $currentView = 'documentos';

        // --- 1. Obtención y Carga Inicial de Documentos ---

        // Cargar laboratorios y productos (necesitamos los IDs del Pivot)
        $clienteEmpresa->load([
            'laboratorios.productos' => function ($query) {
                // Solo necesitamos el ID del pivot
                $query->withPivot(['id']);
            }
        ]);

        // Obtener IDs de todos los Pivots únicos (LaboratorioProducto)
        $pivotIds = $clienteEmpresa->laboratorios
            ->flatMap(function ($laboratorio) {
                return $laboratorio->productos;
            })
            ->pluck('pivot.id')
            ->unique()
            ->filter(); // Eliminar nulos si los hubiera

        // Obtener todos los documentos directamente usando los IDs de los Pivots.
        // Esto es más eficiente que iterar colecciones grandes.
        $allDocuments = \App\Models\Documento::whereIn('laboratorio_producto_id', $pivotIds)
            // Carga ansiosa para mostrar Laboratorio en la tabla.
            ->with(['laboratorioProducto.laboratorio'])
            ->get();

        // --- 2. Aplicar Búsqueda (Filtro) ---

        $searchQuery = $request->get('q');
        if ($searchQuery) {
            $searchQuery = mb_strtolower($searchQuery);
            $allDocuments = $allDocuments->filter(function ($doc) use ($searchQuery) {
                // Filtra por el nombre del documento, o cualquier otro campo relevante.
                // Usamos mb_strtolower para asegurar la compatibilidad con tildes, etc.
                return mb_stripos(mb_strtolower($doc->nombre), $searchQuery) !== false;
            });
        }

        // --- 3. Aplicar Paginación Manual (Colección) ---

        $perPage = $request->get('per_page', 10);
        $page = LengthAwarePaginator::resolveCurrentPage(); // Obtiene el número de página de la URL ('page')

        // 3.1 Crear la colección para la página actual
        $currentPageDocuments = $allDocuments->slice(($page - 1) * $perPage, $perPage)->values();

        // 3.2 Crear el Paginador
        $docs = new LengthAwarePaginator(
            $currentPageDocuments,
            $allDocuments->count(),
            $perPage,
            $page,
            [
                'path' => route('cliente.documentos.index', $clienteEmpresa->id) // Ruta base para los enlaces
            ]
        );

        // Asegurar que los parámetros de búsqueda/tamaño de página se mantengan en los enlaces de paginación
        $docs->appends($request->except('page'));


        // --- 4. Determinar Vista a Devolver ---

        // Si la solicitud es AJAX (lo que indica que viene de tu función loadDocumentos),
        // devolvemos solo el Blade parcial con la tabla.
        if ($request->ajax()) {
            return view('cliente_empresa.documentos_content', [
                'clienteEmpresa' => $clienteEmpresa,
                'docs' => $docs,
                'currentView' => $currentView,
            ]);
        }

        // Si no es AJAX (primera carga), devolvemos la vista principal.
        return view('cliente_empresa.show', [
            'clienteEmpresa' => $clienteEmpresa,
            'currentView' => $currentView,
            'allDocuments' => $docs, // Usamos la colección paginada para la vista inicial
        ]);
    }
}
