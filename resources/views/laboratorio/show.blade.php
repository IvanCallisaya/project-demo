@extends('layouts.app')

@section('title','Laboratorio: '.$laboratorio->nombre)

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
    <div class="alert alert-danger">Error: Revise los campos del formulario de asignación.</div>
    @endif

    <div class="card">
        <div class="card-header">
            {{-- Usamos row para una mejor distribución de espacio --}}
            <div class="row align-items-center">

                {{-- COLUMNA PRINCIPAL DE INFORMACIÓN --}}
                <div class="col-md-8 col-lg-9">

                    {{-- Nombre del Laboratorio (Título Principal) --}}
                    <h1 class="card-title mb-1" style="font-size: 2.25rem;">{{ $laboratorio->nombre }}</h1>

                    <div class="d-flex flex-wrap gap-4 text-muted">

                        {{-- Código o Identificador --}}
                        @if($laboratorio->responsable ?? false)
                        <div>
                            <i class="fa-solid fa-person me-1"></i>
                            <strong>Responsable:</strong> {{ $laboratorio->responsable }}
                        </div>
                        @endif

                        {{-- Dirección --}}
                        <div>
                            <i class="fa-solid fa-location-dot me-1"></i>
                            {{ $laboratorio->direccion }}
                        </div>

                        {{-- Teléfono (si existe) --}}
                        @if($laboratorio->telefono ?? false)
                        <div>
                            <i class="fa-solid fa-phone me-1"></i>
                            {{ $laboratorio->telefono }}
                        </div>
                        @endif

                        {{-- Email (si existe) --}}
                        @if($laboratorio->email ?? false)
                        <div>
                            <i class="fa-solid fa-envelope me-1"></i>
                            {{ $laboratorio->email }}
                        </div>
                        @endif

                    </div>

                </div>


            </div>
        </div>

        <div class="card-body">
            <h5>Productos asignados</h5>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidad de medida</th>
                        <th>Codigo</th>
                        <th>Costo Analisis</th>
                        <th>Tiempo Entrega (Días)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laboratorio->productos as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->unidad_medida }}</td>
                        <td>{{ $p->codigo }}</td>
                        <td>{{ $p->pivot->costo_analisis }}</td>
                        <td>{{ $p->pivot->tiempo_entrega_dias}}</td>
                        <td class="d-flex gap-1">
                            {{-- BOTÓN EDITAR PIVOT: Le pasamos el ID del registro pivot --}}
                            @php
                            $laboratorioProductoId = $p->pivot->id;
                            @endphp

                            <a href="#" class="btn btn-sm btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#uploadModal{{ $p->pivot->id }}">
                                Subir Documento
                            </a>


                            <div class="modal fade" id="uploadModal{{ $p->pivot->id }}" tabindex="-1">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Subir Documento</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa-solid fa-x"></i></button>
                                            
                                        </div>

                                        {{-- FORMULARIO DE SUBIDA --}}
                                        <form action="{{ route('documento.subir', $p->pivot->id ) }}" method="POST" enctype="multipart/form-data">

                                            {{-- TOKEN CSRF REQUERIDO PARA RUTAS POST EN LARAVEL --}}
                                            @csrf

                                            <div class="modal-body">
                                                <p>Subiendo documento </p>
                                                <div class="form-group">
                                                    <label for="documento_{{ $p->pivot->id }}">Seleccionar Archivo (máx 20MB)</label>
                                                    {{-- MUY IMPORTANTE: El nombre del campo es 'documento', que es el nombre validado en el controlador --}}
                                                    <input type="file" class="form-control-file" id="documento_{{ $p->pivot->id }}" name="documento" required>
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
                            <a href="{{ route('laboratorio.producto.edit_pivot',[$laboratorio->id, $p->pivot->id]) }}"
                                class="btn btn-sm btn-warning ml-2"><i class="fa-regular fa-pen-to-square" style="color: white;"></i></a>
                            {{-- BOTÓN REMOVER: Le pasamos el ID del registro pivot --}}
                            <form method="POST" action="{{ route('laboratorio.producto.detach',[$laboratorio->id, $p->pivot->id]) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm ml-2" onclick="return confirm('¿Remover este registro de inventario?')"><i class="fa-solid fa-trash-can" style="color:white"></button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <h5>Asignar producto existente</h5>
            <form method="POST" action="{{ route('laboratorio.producto.attach',$laboratorio->id) }}">
                @csrf
                <div class="row g-2 align-items-end">

                    {{-- Producto ID (Select2) --}}
                    <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        <select name="producto_id" class="form-control select2 @error('producto_id') is-invalid @enderror" required>
                            <option value="">Seleccionar producto...</option>
                            @foreach(\App\Models\Producto::orderBy('nombre')->get() as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nombre }} ({{ $prod->codigo }})</option>
                            @endforeach
                        </select>
                        @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- F. Costo Análisis (para valores decimales/monetarios) -->
                    <div class="col-md-2">
                        <label class="form-label">F. Costo Análisis</label>
                        {{-- Se usa type="number" con step="0.01" o step="any" para permitir decimales --}}
                        <input type="number" name="costo_analisis" class="form-control @error('costo_analisis') is-invalid @enderror" value="{{ old('costo_analisis') }}" step="0.01" min="0" />
                        @error('costo_analisis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tiempo Entrega Dias (para valores enteros) --}}
                    <div class="col-md-2">
                        <label class="form-label">F. Tiempo Entrega Dias</label>
                        {{-- Se usa type="number" con step="1" para aceptar solo números enteros --}}
                        <input type="number" name="tiempo_entrega_dias" class="form-control @error('tiempo_entrega_dias') is-invalid @enderror" value="{{ old('tiempo_entrega_dias') }}" step="1" min="0" />
                        @error('tiempo_entrega_dias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button class="btn btn-primary">Asignar Producto</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
    </div>
</div>
@endsection
@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endpush