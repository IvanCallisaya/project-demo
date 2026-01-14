@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reporte de Documentos y Plazos</h6>
        </div>
        <div class="card-body">
            {{-- Formulario de Filtros --}}
            <form method="GET" action="{{ route('reporte.documento.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="small">Buscar Documento / Producto</label>
                    <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Ej: Certificado...">
                </div>

                <div class="col-md-2">
                    <label class="small">Filtrar por tipo de Fecha</label>
                    <select name="tipo_fecha" class="form-control">
                        <option value="entrega" {{ request('tipo_fecha') == 'entrega' ? 'selected' : '' }}>Plazo de Entrega</option>
                        <option value="recojo" {{ request('tipo_fecha') == 'recojo' ? 'selected' : '' }}>Fecha de Recojo</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="small">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>

                <div class="col-md-2">
                    <label class="small">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2">Filtrar</button>
                    <a href="{{ route('reporte.documento.index') }}" class="btn btn-secondary w-100">Limpiar</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Documento</th>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Plazo Entrega</th>
                            <th>Fecha Recojo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentos as $doc)
                        <tr>
                            <td>
                                <strong>{{ $doc->nombre }}</strong>
                                <br><small class="text-muted">Subido: {{ $doc->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>{{ $doc->producto->nombre ?? 'N/A' }}</td>
                            <td>{{ $doc->producto->clienteEmpresa->nombre ?? 'N/A' }}</td>
                            <td class="{{ Carbon\Carbon::parse($doc->fecha_plazo_entrega)->isPast() ? 'text-danger fw-bold' : '' }}">
                                {{ $doc->fecha_plazo_entrega ? Carbon\Carbon::parse($doc->fecha_plazo_entrega)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                {{ $doc->fecha_recojo ? Carbon\Carbon::parse($doc->fecha_recojo)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-center">
                                @if($doc->url)
                                <a href="{{ $doc->url }}" target="_blank" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-external-link-alt"></i> Ver Drive
                                </a>
                                @else
                                <span class="badge badge-secondary">Sin URL</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron documentos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="small text-muted">
                            Mostrando {{ $documentos->firstItem() }} a {{ $documentos->lastItem() }}
                            de {{ $documentos->total() }} resultados
                        </p>
                    </div>
                    <div>
                        {{-- Esto genera los botones --}}
                        {{ $documentos->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection