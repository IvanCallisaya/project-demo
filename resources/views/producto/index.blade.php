@extends('layouts.app')
@section('title','Productos')
@section('content')

<div class="container-fluid">
    <h1>Productos</h1>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                <form method="GET" action="{{ route('laboratorio.index') }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre..."
                        value="{{ request('q') }}">

                    <div class="d-flex gap-2 flex-shrink-0">

                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(request('per_page',10)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-secondary flex-shrink-0 ml-2">Buscar</button>
                    </div>
                </form>

                <a href="{{ route('producto.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-2">
                    Nuevo
                </a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Unidad Medida</th>
                        <th>Subcategoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $p)
                    <tr>
                        <td>{{ $p->codigo }}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->unidad_medida }}</td>
                        <td>{{ $p->subcategoria->nombre }}</td>
                        <td>
                            <a href="{{ route('producto.edit',$p->id) }}" class="btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square" style="color: white;"></i></a>
                            <form action="{{ route('producto.destroy',$p->id) }}"
                                method="POST"
                                style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar producto?')">
                                    <i class="fa-solid fa-trash-can" style="color:white"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <div>Mostrando {{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }}</div>
            <div>{{ $productos->links() }}</div>
        </div>
    </div>
</div>

@endsection