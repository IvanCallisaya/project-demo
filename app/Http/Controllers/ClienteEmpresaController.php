<?php

namespace App\Http\Controllers;

use App\Models\ClienteEmpresa;
use App\Models\ContactoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClienteEmpresaController extends Controller
{
    public function index()
    {
        $empresas = ClienteEmpresa::with('contactos')->get();
        return view('cliente_empresa.index', compact('empresas'));
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
            'telefono' => $request->telefono,
            'empresa_id' => 1,
            'imagen' => $rutaImagen, // Se guarda la ruta
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

        $empresa->update($request->only(['nombre', 'direccion', 'telefono', 'empresa_id']));

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
