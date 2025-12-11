@extends('layouts.app')

@section('title','Clientes Empresa')

@section('content')
<div class="container-fluid">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h1>Clientes</h1>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                <form method="GET" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <input type="text" name="search" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre o teléfono"
                        value="{{ $search ?? '' }}">

                    <div class="d-flex gap-2 flex-shrink-0">
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>

                        <button class="btn btn-secondary flex-shrink-0 ml-2">Buscar</button>
                    </div>
                </form>

                <a href="{{ route('cliente_empresa.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-2">
                    Nuevo
                </a>
            </div>
        </div>
        {{-- FIN DEL BLOQUE DE BÚSQUEDA Y BOTÓN NUEVO --}}

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Contactos</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($empresas as $e)
                    <tr>
                        <td class="text-center">
                            @if($e->imagen)
                            <img src="{{ asset('storage/'.$e->imagen) }}"
                                width="60" height="60"
                                class="rounded"
                                style="object-fit:cover;">
                            @else
                            <small class="text-muted">Sin imagen</small>
                            @endif
                        </td>

                        <td>{{ $e->nombre }}</td>

                        <td>{{ $e->telefono_principal }}</td>

                        <td>
                            @foreach($e->contactos as $c)
                            <div>
                                • {{ $c->nombre }}
                                <small class="text-muted">({{ $c->telefono }})</small>
                            </div>
                            @endforeach
                        </td>

                        <td class="text-center">
                            <a href="{{ route('cliente_empresa.show',$e->id) }}" class="btn btn-sm btn-info"><i class="fa-regular fa-eye" style="color:white"></i></a>
                            <a href="{{ route('cliente_empresa.edit',$e->id) }}"
                                class="btn btn-sm btn-warning ">
                                <i class="fa-regular fa-pen-to-square" style="color:white"></i>
                            </a>

                            <form action="{{ route('cliente_empresa.destroy',$e->id) }}"
                                method="POST"
                                style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar cliente?')">
                                    <i class="fa-solid fa-trash-can" style="color:white"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No se encontraron resultados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>Mostrando {{ $empresas->firstItem() ?? 0 }} - {{ $empresas->lastItem() ?? 0 }} de {{ $empresas->total() }}</div>
                <div>{{ $empresas->links() }}</div>
            </div>

        </div>
    </div>
</div>
@endsection