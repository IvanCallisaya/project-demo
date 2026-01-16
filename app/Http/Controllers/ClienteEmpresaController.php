<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use App\Models\Documento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
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
            'nit' => $request->nit,
            'direccion' => $request->direccion,
            'actividad_principal' => $request->actividad_principal,
            'id_padron' => $request->id_padron,
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

        $empresa->update($request->only(['nombre', 'direccion', 'telefono', 'nombre_contacto_principal', 'email_principal', 'telefono_principal', 'actividad_principal', 'nit', 'id_padron']));

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
            ->paginate($perPage)
            ->withQueryString();

        $currentView = 'laboratorios';

        return view('cliente_empresa.show', [
            'clienteEmpresa' => $clienteEmpresa,
            'currentView' => $currentView,
            'labs' => $laboratoriosPaginados,
        ]);
    }



    public function documentosIndex(Request $request, ClienteEmpresa $clienteEmpresa)
    {
        $currentView = 'documentos';
        $searchQuery = $request->get('q');
        $perPage = $request->get('per_page', 10);

        // 1. Iniciamos la consulta desde el modelo Documento
        // Filtramos los documentos cuyo producto pertenece al cliente actual
        $query = Documento::whereHas('producto', function ($q) use ($clienteEmpresa) {
            $q->where('cliente_empresa_id', $clienteEmpresa->id);
        })->with(['producto.laboratorioTitular']); // Carga ansiosa para mostrar info en la tabla

        // 2. Aplicar Filtro de búsqueda (Directamente en la Query de BD, mucho más rápido)
        if ($searchQuery) {
            $query->where('nombre', 'like', '%' . $searchQuery . '%');
        }

        // 3. Paginación automática de Eloquent (Ya no necesitas LengthAwarePaginator manual)
        $docs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Mantener parámetros de búsqueda en los links de paginación
        $docs->appends($request->except('page'));

        // 4. Determinar Vista a Devolver
        if ($request->ajax()) {
            return view('cliente_empresa.documentos_content', [
                'clienteEmpresa' => $clienteEmpresa,
                'docs' => $docs,
                'currentView' => $currentView,
            ]);
        }

        return view('cliente_empresa.show', [
            'clienteEmpresa' => $clienteEmpresa,
            'currentView' => $currentView,
            'allDocuments' => $docs,
        ]);
    }

    // Sucursales
    public function sucursalesIndex(Request $request, ClienteEmpresa $clienteEmpresa)
    {
        $currentView = 'sucursales';
        $q = $request->input('q');
        $perPage = $request->input('per_page', 10);

        $sucursales = $clienteEmpresa->sucursales()
            ->when($q, function ($query) use ($q) {
                $query->where('nombre', 'like', "%$q%");
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('cliente_empresa.show', compact('clienteEmpresa', 'currentView', 'sucursales'));
    }

    // Productos
    public function productosIndex(Request $request, ClienteEmpresa $clienteEmpresa)
    {
        $currentView = 'productos';
        $search = $request->input('q'); // Texto de búsqueda
        $estadoId = $request->input('estado');
        $perPage = $request->input('per_page', 10);

        $query = $clienteEmpresa->productos()->with(['subcategoria', 'laboratorioTitular']);

        // 1. Filtro por Estado (Numérico)
        if ($estadoId !== null && $estadoId !== '') {
            $query->where('estado', (int)$estadoId);
        }

        // 2. Filtro de Búsqueda General (Nombre, Código o ID Presolicitud)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%")
                    ->orWhere('id_presolicitud', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('cliente_empresa.show', compact('clienteEmpresa', 'productos', 'currentView'));
    }
    public function enviarNotificacionRevision(Request $request)
    {
        $request->validate([
            'destino' => 'required|email',
            'mensaje' => 'required',
        ]);

        try {
            // USAR 127.0.0.1 para evitar que Apache intente interceptar la IP pública
            $url = 'http://127.0.0.1:3000/api/zeptomail/send';

            $response = Http::post($url, [
                'to'      => $request->destino,
                'subject' => 'Notificación de Revisión',
                // Reutilizamos el diseño HTML que ya tienes en el comando
                'message' => $this->generarPlantillaHTML($request->mensaje),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true]);
            }

            // Si falla, vemos qué dijo Node
            return response()->json(['error' => $response->body()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Función auxiliar para darle el diseño bonito que creamos antes
     */
    private function formatearMensajeHTML($texto)
    {
        return "
    <div style='font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; border: 1px solid #e1e1e1;'>
            <div style='background-color: #6c2bd9; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 20px;'>Notificación de Revisión</h1>
            </div>
            <div style='padding: 30px;'>
                <p style='font-size: 16px; color: #333;'>Se ha generado una nueva actualización en su proceso:</p>
                <div style='background-color: #f9f9f9; border-left: 4px solid #6c2bd9; padding: 15px; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 15px; color: #555;'>$texto</p>
                </div>
            </div>
        </div>
    </div>";
    }
}
