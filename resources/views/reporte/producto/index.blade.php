@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Reporte General de Productos</h6>
            {{-- Botón opcional para exportar --}}
        </div>
        <div class="card-body">
            {{-- Formulario de Filtros --}}
            <form method="GET" action="{{ route('reporte.producto') }}" class="row g-2 mb-4">
                <div class="col-12 col-md-3">
                    <input type="text" name="buscar" class="form-control" placeholder="Nombre o Registro Nro." value="{{ request('buscar') }}">
                </div>
                <div class="col-6 col-md-2">
                    <select name="categoria_id" class="form-control" id="categoria_select">
                        <option value="">-- Categoría --</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="subcategoria_id" class="form-control">
                        <option value="">-- Subcategoría --</option>
                        @foreach($subcategorias ?? [] as $sub)
                        <option value="{{ $sub->id }}" {{ request('subcategoria_id') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <input type="text" name="razon_social" class="form-control" placeholder="Cliente" value="{{ request('razon_social') }}">
                </div>
                <div class="col-12 col-md-2">
                    <input type="text" name="laboratorio" class="form-control" placeholder="Laboratorio" value="{{ request('laboratorio') }}">
                </div>
                <div class="col-12 col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>

            <hr>

            {{-- CONTENEDOR RESPONSIVO --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100">
                    <thead class="thead-dark">
                        <tr class="text-nowrap">
                            <th>Registro Nro.</th>
                            <th>Nombre Producto</th>
                            <th>Clase</th>
                            <th>Laboratorios (T/P)</th>
                            <th>País Origen</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $p)
                        <tr>
                            <td class="text-nowrap"><strong>{{ $p->codigo }}</strong></td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->subcategoria->categoria->nombre ?? 'N/A' }}</td>
                            <td>
                                <div style="min-width: 200px;">
                                    <div class="small"><strong>T:</strong> {{ $p->laboratorioTitular->nombre ?? 'N/A' }}</div>
                                    <div class="small"><strong>P:</strong> {{ $p->laboratorioProduccion->nombre ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                @php
                                $paisTitular = $p->laboratorioTitular->pais ?? null;
                                $paisProd = $p->laboratorioProduccion->pais ?? null;
                                $paises = collect([$paisTitular, $paisProd])->filter()->unique();
                                @endphp
                                <span class="badge badge-light border">
                                    {{ $paises->isEmpty() ? 'No registrado' : $paises->implode(' / ') }}
                                </span>
                            </td>
                            <td>{{ $p->clienteEmpresa->nombre_empresa ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No se encontraron productos con los filtros seleccionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection