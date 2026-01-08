<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\ClienteEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SucursalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Filtramos por la empresa del usuario logueado
        $sucursales = Sucursal::with('clienteEmpresa')
            ->when($search, function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('telefono_principal', 'like', "%$search%")
                    ->orWhere('nombre_contacto_principal', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);
            Log::info("Sucursales encontradas: " . $sucursales->toJson());
        return view('sucursal.index', compact('sucursales', 'search', 'perPage'));
    }

    public function create()
    {
        // Necesitamos los clientes para el select del formulario
        $clientes = ClienteEmpresa::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('sucursal.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_empresa_id' => 'required|exists:cliente_empresa,id',
            'nombre'             => 'required|string|max:255',
            'direccion'          => 'nullable|string',
            'email_principal'    => 'nullable|email',
        ]);

        // Combinamos los datos del form con el ID de la empresa del usuario
        Sucursal::create(array_merge($request->all(), [
            'empresa_id' => auth()->user()->empresa_id
        ]));

        return redirect()->route('sucursal.index')
            ->with('success', 'Sucursal creada correctamente.');
    }

    public function edit($id)
    {
        $sucursal = Sucursal::where('empresa_id', auth()->user()->empresa_id)->findOrFail($id);
        $clientes = ClienteEmpresa::where('empresa_id', auth()->user()->empresa_id)->get();

        return view('sucursal.edit', compact('sucursal', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::where('empresa_id', auth()->user()->empresa_id)->findOrFail($id);

        $request->validate([
            'cliente_empresa_id' => 'required|exists:cliente_empresa,id',
            'nombre'             => 'required|string|max:255',
        ]);

        $sucursal->update($request->all());

        return redirect()->route('sucursal.index')
            ->with('success', 'Sucursal actualizada correctamente.');
    }

    public function destroy($id)
    {
        $sucursal = Sucursal::where('empresa_id', auth()->user()->empresa_id)->findOrFail($id);
        $sucursal->delete();

        return back()->with('success', 'Sucursal eliminada correctamente.');
    }
}
