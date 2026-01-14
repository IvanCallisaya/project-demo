@extends('layouts.app')

@section('title', 'Reporte de Pre-Solicitudes')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Reporte General de Pre-Solicitudes</h6>

        </div>
        <div class="card-body">
            {{-- Formulario de Filtros --}}
            <form method="GET" action="{{ route('reporte.presolicitud') }}" class="row g-3 mb-4">

                {{-- Buscador General --}}
                <div class="col-12 col-md-3">
                    <label class="small mb-1">Buscar</label>
                    <input type="text" name="buscar" class="form-control" placeholder="ID, Cliente o Sucursal" value="{{ request('buscar') }}">
                </div>

                {{-- Filtro de Estado --}}
                <div class="col-6 col-md-2">
                    <label class="small mb-1">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach($estados as $id => $nombre)
                        <option value="{{ $id }}" {{ request('estado') == $id ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha Desde (con minutos) --}}
                <div class="col-6 col-md-2">
                    <label class="small mb-1">Desde</label>
                    <input type="datetime-local" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>

                {{-- Fecha Hasta (con minutos) --}}
                <div class="col-6 col-md-2">
                    <label class="small mb-1">Hasta</label>
                    <input type="datetime-local" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>

                {{-- Botones de Acción --}}
                <div class="col-12 col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('reporte.presolicitud') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </a>
                </div>
            </form>

            <hr>

            {{-- CONTENEDOR RESPONSIVO --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100">
                    <thead class="bg-light">
                        <tr class="text-nowrap text-center">
                            <th>ID Solicitud</th>
                            <th>Fecha de Solicitud</th>
                            <th>Cliente / Empresa</th>
                            <th>Sucursal</th>
                            <th>Solicitado por</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preSolicitudes as $solicitud)
                        <tr>
                            <td class="text-center font-weight-bold">{{ $solicitud->id_presolicitud }}</td>
                            <td class="text-center">
                                {{ $solicitud->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $solicitud->clienteEmpresa->nombre ?? 'N/A' }}</td>
                            <td>{{ $solicitud->sucursal->nombre ?? 'N/A' }}</td>
                            <td>
                                @php
                                $primeraBitacora = $solicitud->bitacoras->first();
                                @endphp
                                {{ $primeraBitacora->usuario->name ?? 'Sistema' }}
                                </tds>
                            <td>
                                <span class="badge" style="background-color: {{ $solicitud->estado_color ?? '#6c757d' }}; color: white;">
                                    {{ $solicitud->estado_nombre ?? $solicitud->estado }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                No se encontraron pre-solicitudes con los criterios seleccionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="small text-muted">
                            Mostrando {{ $preSolicitudes->firstItem() }} a {{ $preSolicitudes->lastItem() }}
                            de {{ $preSolicitudes->total() }} resultados
                        </p>
                    </div>
                    <div>
                        {{-- Esto genera los botones --}}
                        {{ $preSolicitudes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $preSolicitudes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection