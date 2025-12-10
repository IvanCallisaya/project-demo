<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use App\Models\ContactoCliente;
use Illuminate\Http\Request;
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
            'nombre_contacto_principal'=> $request->nombre_contacto_principal,
            'email_principal'=> $request->email_principal,
            'telefono_principal'=> $request->telefono_principal,
            'empresa_id' => 1,
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
}
