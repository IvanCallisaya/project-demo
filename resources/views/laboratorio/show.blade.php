@extends('layouts.app')

@section('title','Laboratorio: '.$laboratorio->nombre)

@section('content')
<div class="container-fluid">

    {{-- MENSAJES DE ALERTA --}}
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
    <div class="alert alert-danger">Error: Revise los campos del formulario de asignación.</div>
    @endif

    {{-- BOTÓN DE RETORNO --}}
    <a href="javascript:history.back()"
        class="btn btn-secondary mb-3 shadow-sm"
        style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-2"></i> Volver Atrás
    </a>

    <div class="card">

        {{-- CARD HEADER: INFORMACIÓN DEL LABORATORIO --}}
        <div class="card-header border-bottom-0" style="padding-bottom: 0.5rem;">
            <div class="row align-items-start">

                {{-- COLUMNA PRINCIPAL DE INFORMACIÓN (9/12) --}}
                <div class="col-md-12 col-lg-9">

                    {{-- TÍTULO PRINCIPAL: Nombre del Laboratorio --}}
                    <h1 class="card-title mb-2" style="font-size: 2.25rem; font-weight: 700; color: #333;">
                        <i class="fas fa-flask text-primary me-2"></i> {{ $laboratorio->nombre }}
                    </h1>

                    {{-- 1. SECCIÓN DE DATOS CLAVE (GRID RESPONSIVO) --}}
                    <div class="row row-cols-sm-1 row-cols-md-2 row-cols-lg-2 gx-4" style="font-size: 0.95rem;">

                        {{-- ETIQUETA 1: REGISTRO SENASAG (Se apila en SM) --}}
                        @if($laboratorio->registro_senasag ?? false)
                        <div class="col">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fa-solid fa-stamp me-1 text-info"></i>
                                <strong class="me-1">REGISTRO:</strong>
                                <span>{{ $laboratorio->registro_senasag }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- ETIQUETA 2: RESPONSABLE (Se apila en SM) --}}
                        @if($laboratorio->responsable ?? false)
                        <div class="col">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fa-solid fa-person me-1 text-success"></i>
                                <strong class="me-1">RESPONSABLE:</strong>
                                <span>{{ $laboratorio->responsable }}</span>
                            </div>
                        </div>
                        @endif
                    </div>



                    {{-- 2. SECCIÓN DE CONTACTO Y UBICACIÓN (GRID Ajustable) --}}
                    <div class="row row-cols-md-auto row-cols-1 gx-4 gy-2 text-secondary" style="font-size: 0.9rem;">

                        {{-- DIRECCIÓN Y CIUDAD --}}
                        <div class="col">
                            <i class="fa-solid fa-location-dot me-1"></i>
                            <strong>Ubicación:</strong>
                            <span>{{ $laboratorio->direccion }} ({{ $laboratorio->ciudad }})</span>
                        </div>

                        {{-- TELÉFONO --}}
                        @if($laboratorio->telefono ?? false)
                        <div class="col">
                            <i class="fa-solid fa-phone me-1"></i>
                            <strong>Teléfono:</strong>
                            <a href="tel:{{ $laboratorio->telefono }}" class="text-secondary text-decoration-none">{{ $laboratorio->telefono }}</a>
                        </div>
                        @endif

                        {{-- EMAIL --}}
                        @if($laboratorio->email ?? false)
                        <div class="col">
                            <i class="fa-solid fa-envelope me-1"></i>
                            <strong>Email:</strong>
                            <a href="mailto:{{ $laboratorio->email }}" class="text-secondary text-decoration-none">{{ $laboratorio->email }}</a>
                        </div>
                        @endif
                    </div>

                </div>

                {{-- COLUMNA DE BOTONES/ESTADO (3/12) --}}
                <div class="col-md-12 col-lg-3 text-lg-end mt-3 mt-lg-0">
                    {{-- Botón de Acción Principal (Ej: Editar) --}}
                    <a href="{{ route('laboratorio.edit', $laboratorio->id) }}" class="btn btn-warning mt-2 shadow-sm w-100 w-lg-auto">
                        <i class="fa-regular fa-pen-to-square me-1"></i> Editar
                    </a>
                </div>

            </div>
        </div>

        {{-- CARD BODY: TABLA DE PRODUCTOS Y FORMULARIO --}}
        <div class="card-body">

            <h5>Productos Asignados</h5>

            {{-- Hacemos la tabla RESPONSIVA --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>U. de medida</th>
                            <th>Código</th>
                            <th>Costo Análisis</th>
                            <th>Tiempo Entrega (Días)</th>
                            <th>Fecha Entrega</th>
                            <th>Estado</th>
                            <th style="min-width: 280px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laboratorio->productos as $p)
                        <tr>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->unidad_medida }}</td>
                            <td>{{ $p->codigo }}</td>
                            <td>{{ $p->pivot->costo_analisis }}</td>
                            <td>{{ $p->pivot->tiempo_entrega_dias}}</td>
                            <td>{{ $p->pivot->fecha_entrega}}</td>
                            <td>
                                <span class="badge bg-{{ $p->pivot->estado_badge_class }}">
                                    {{ $p->pivot->estado_nombre }}
                                </span>
                            </td>

                            <td class="d-flex gap-1">
                                @php
                                $laboratorioProductoId = $p->pivot->id;
                                @endphp

                                {{-- BOTÓN SUBIR DOCUMENTO (MODAL) --}}
                                <button type="button" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#uploadModal{{ $p->pivot->id }}">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Subir Documento
                                </button>

                                {{-- MODAL DE SUBIDA DE DOCUMENTOS --}}
                                <div class="modal fade" id="uploadModal{{ $p->pivot->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Subir Documento ({{ $p->nombre }})</h5>
                                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-x"></i></button>
                                            </div>

                                            {{-- FORMULARIO DE SUBIDA --}}
                                            <form action="{{ route('documento.subir', $p->pivot->id ) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label for="documento_{{ $p->pivot->id }}">Archivo (máx 20MB)</label>
                                                        <input type="file" class="form-control-file" id="documento_{{ $p->pivot->id }}" name="documento" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Fecha Plazo de Entrega</label>
                                                        <input type="date" name="fecha_plazo_entrega" class="form-control @error('fecha_plazo_entrega') is-invalid @enderror" value="{{ old('fecha_plazo_entrega') }}" />
                                                        @error('fecha_plazo_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Fecha de Recojo</label>
                                                        <input type="date" name="fecha_recojo" class="form-control @error('fecha_recojo') is-invalid @enderror" value="{{ old('fecha_recojo') }}" />
                                                        @error('fecha_recojo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Subir y Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- FIN MODAL --}}

                                {{-- BOTÓN EDITAR PIVOT --}}
                                <a href="{{ route('laboratorio.producto.edit_pivot',[$laboratorio->id, $p->pivot->id]) }}"
                                    class="btn btn-sm btn-warning ml-2">
                                    <i class="fa-regular fa-pen-to-square" style="color: white;"></i>
                                </a>

                                {{-- BOTÓN REMOVER PIVOT --}}
                                <form method="POST" action="{{ route('laboratorio.producto.detach',[$laboratorio->id, $p->pivot->id]) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm ml-2" onclick="return confirm('¿Está seguro de remover el producto {{ $p->nombre }} de este laboratorio?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay productos asignados a este laboratorio.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- FIN table-responsive --}}

            <hr>

            {{-- FORMULARIO ASIGNAR PRODUCTO --}}
            <h5>Asignar producto existente</h5>
            <form method="POST" action="{{ route('laboratorio.producto.attach',$laboratorio->id) }}">
                @csrf
                <div class="row g-2 align-items-end">

                    {{-- Producto ID (Select2) --}}
                    <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        {{-- Asegúrate de inicializar Select2 en @push('js') si lo estás usando --}}
                        <select name="producto_id" class="form-control select2 @error('producto_id') is-invalid @enderror" required>
                            <option value="">Seleccionar producto...</option>
                            @foreach(\App\Models\Producto::orderBy('nombre')->get() as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nombre }} ({{ $prod->codigo }})</option>
                            @endforeach
                        </select>
                        @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Costo Análisis</label>
                        <input type="number" name="costo_analisis" class="form-control @error('costo_analisis') is-invalid @enderror" value="{{ old('costo_analisis') }}" step="0.01" min="0" />
                        @error('costo_analisis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tiempo Entrega Dias --}}
                    <div class="col-md-2">
                        <label class="form-label">Tiempo Entrega Días</label>
                        <input type="number" name="tiempo_entrega_dias" class="form-control @error('tiempo_entrega_dias') is-invalid @enderror" value="{{ old('tiempo_entrega_dias') }}" step="1" min="0" />
                        @error('tiempo_entrega_dias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha Entrega --}}
                    <div class="col-md-2">
                        <label class="form-label">Fecha Entrega</label>
                        <input type="date" name="fecha_entrega" class="form-control @error('fecha_entrega') is-invalid @enderror" value="{{ old('fecha_entrega') }}" />
                        @error('fecha_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Asignar</button>
                    </div>

                </div>
            </form>
            <hr>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    // Aquí iría la inicialización de Select2 si la estás usando
    // Ejemplo:
    /*
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    */
</script>
@endpush