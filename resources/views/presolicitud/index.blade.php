@extends('layouts.app')

@section('title', 'Listado de Pre-Solicitudes')

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif

    <h1>Pre-Solicitudes</h1>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                <form method="GET" action="{{ route('presolicitud.index') }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1">
                        <input type="text" name="search" class="form-control flex-grow-1"
                            placeholder="Buscar por ID o Trámite..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0">
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-secondary">Buscar</button>
                    </div>
                </form>

                <a href="{{ route('presolicitud.create') }}" class="btn btn-primary flex-shrink-0">
                    Nueva
                </a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pre-solicitud</th>
                        <th>Fecha Solicitud</th>
                        <th>Trámite</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $p)
                    <tr>
                        <td>{{ $p->id_presolicitud }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $p->tramite }}</strong></td>
                        <td>
                            <div class="dropdown">
                                <span class="badge dropdown-toggle"
                                    id="statusDropdown{{ $p->id }}"
                                    style="background-color: {{ $p->estado_color }}; color: white; cursor: pointer;"
                                    data-bs-toggle="dropdown">
                                    {{ $p->estado_nombre }}
                                </span>

                                <div class="dropdown-menu">
                                    <h6 class="dropdown-header" style="font-size: 0.75rem;">Cambiar Estado</h6>

                                    {{-- Opción SOLICITADO --}}
                                    <form action="{{ route('producto.cambiarEstado', $p->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="nuevo_estado" value="{{ App\Models\Producto::SOLICITADO }}">
                                        <button type="submit" class="dropdown-item" style="font-size: 0.8rem; padding: 0.25rem 1rem;">Solicitado</button>
                                    </form>

                                    {{-- Opción APROBADO --}}
                                    <form action="{{ route('producto.cambiarEstado', $p->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="nuevo_estado" value="{{ App\Models\Producto::APROBADO }}">
                                        <button type="submit" class="dropdown-item" style="font-size: 0.8rem; padding: 0.25rem 1rem;">Aprobado</button>
                                    </form>

                                    {{-- Opción RECHAZADO --}}
                                    <form action="{{ route('producto.cambiarEstado', $p->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="nuevo_estado" value="{{ App\Models\Producto::RECHAZADO }}">
                                        <button type="submit" class="dropdown-item text-danger" style="font-size: 0.8rem; padding: 0.25rem 1rem;">Rechazado</button>
                                    </form>
                                </div>
                        </td>
                        <td style="white-space:nowrap">
                            <a href="{{ route('producto.edit',$p->id) }}" class="btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square" style="color: white;"></i></a>
                            {{-- ... Formulario de eliminar ... --}}
                            <form action="{{ route('producto.destroy',$p->id) }}"
                                method="POST"
                                style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar Producto?')">
                                    <i class="fa-solid fa-trash-can" style="color:white"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay pre-solicitudes registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>Mostrando {{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }}</div>
                <div>{{ $productos->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection