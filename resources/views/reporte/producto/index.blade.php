@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reporte General de Productos</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reporte.producto') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="buscar" class="form-control" placeholder="Nombre o Registro Nro." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2">
                    <select name="categoria_id" class="form-control" id="categoria_select">
                        <option value="">-- Categoría --</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="subcategoria_id" class="form-control">
                        <option value="">-- Subcategoría --</option>
                        @foreach($subcategorias ?? [] as $sub)
                        <option value="{{ $sub->id }}" {{ request('subcategoria_id') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="razon_social" class="form-control" placeholder="Razón Social (Cliente)" value="{{ request('razon_social') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="laboratorio" class="form-control" placeholder="Laboratorio" value="{{ request('laboratorio') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Registro Nro.</th>
                        <th>Nombre Producto</th>
                        <th>Clase</th>
                        <th>Laboratorio Titular / Producido</th>
                        <th>País(es) Origen</th>
                        <th>Razón Social (Cliente)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $p)
                    <tr>
                        <td><strong>{{ $p->codigo }}</strong></td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->subcategoria->categoria->nombre ?? 'N/A' }}</td>
                        <td>
                            <div class="small"><strong>T:</strong> {{ $p->laboratorioTitular->nombre ?? 'N/A' }}</div>
                            <div class="small"><strong>P:</strong> {{ $p->laboratorioProduccion->nombre ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @php
                            // Obtenemos los nombres de países directamente de los campos de texto
                            $paisTitular = $p->laboratorioTitular->pais ?? null;
                            $paisProd = $p->laboratorioProduccion->pais ?? null;

                            $paises = collect([$paisTitular, $paisProd])->filter()->unique();
                            @endphp
                            {{ $paises->isEmpty() ? 'No registrado' : $paises->implode(' / ') }}
                        </td>
                        <td>{{ $p->clienteEmpresa->nombre_empresa ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $productos->links() }}
        </div>
    </div>
</div>
</div>
@endsection