@extends('layouts.app')

@section('title', 'Listado de Subcategorías')

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
    <div class="alert alert-danger">Error: Revise los campos del formulario de asignación.</div>
    @endif

    <h1>Subcategorías</h1>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                <form method="GET" action="{{ route('subcategoria.index') }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1">
                        <input type="text" name="search" class="form-control flex-grow-1"
                            placeholder="Buscar por Subcategoría, Código o Categoría Principal"
                            value="{{ $search ?? '' }}">
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0">

                        {{-- Selector de Paginación --}}
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(($perPage ?? 10)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-secondary flex-shrink-0">Buscar</button>
                    </div>
                </form>

                {{-- BOTÓN NUEVO --}}
                <a href="{{ route('subcategoria.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-md-2">
                    Nuevo
                </a>
            </div>
        </div>
        {{-- FIN DEL BLOQUE DE BÚSQUEDA Y BOTÓN NUEVO --}}

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Subcategoría</th>
                        <th>Codigo</th>
                        <th>Categoría Principal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategorias as $sub)
                    <tr>
                        <td>{{ $sub->id }}</td>
                        <td>{{ $sub->nombre }}</td>
                        <td>{{ $sub->codigo ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $sub->categoria->nombre ?? 'Categoría No Encontrada' }}</strong>
                        </td>
                        <td class="text-center">
                            {{-- Botón Editar --}}
                            <a href="{{ route('subcategoria.edit', $sub->id) }}" class="btn btn-sm btn-warning text-white">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            {{-- Formulario Eliminar --}}
                            <form action="{{ route('subcategoria.destroy', $sub->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta subcategoría?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No se encontraron subcategorías.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>Mostrando {{ $subcategorias->firstItem() ?? 0 }} - {{ $subcategorias->lastItem() ?? 0 }} de {{ $subcategorias->total() }}</div>
                <div>{{ $subcategorias->links() }}</div>
            </div>

        </div>
    </div>
</div>
@endsection