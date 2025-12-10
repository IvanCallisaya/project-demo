<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\ClienteEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaboratorioController extends Controller
{
    // index with search + per_page config
    public function index(Request $r, $cliente_empresa_id = null)
    {
        $perPage = (int) ($r->query('per_page', 10));
        $q = $r->query('q');

        $query = Laboratorio::query()->withCount('productos');

        // optional: filter by cliente_empresa (nested menu requirement)
        if ($cliente_empresa_id) {
            $query->where('cliente_empresa_id', $cliente_empresa_id);
        }

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%");
        }

        $labs = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // pass cliente info if filtering
        $cliente = $cliente_empresa_id ? ClienteEmpresa::find($cliente_empresa_id) : null;

        return view('laboratorio.index', compact('labs', 'cliente', 'perPage'));
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
        $clienteId = $laboratorio->cliente_empresa_id;
        $laboratorio->delete();
        return redirect()->route('cliente_empresa.laboratorios.index', $clienteId)
            ->with('success', 'Laboratorio eliminado.');
    }
}
