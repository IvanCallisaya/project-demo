@extends('layouts.app')
@section('title','Productos')
@section('content')

<div class="container-fluid">
    {{-- Contenedor dinámico para alertas AJAX --}}
    <div id="main-alerts"></div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <h1>Productos</h1>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">
                <form method="GET" action="{{ route('producto.index') }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-85">

                    {{-- Input de búsqueda --}}
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="form-control flex-grow-1"
                        placeholder="Buscar por nombre, código o encargado...">

                    {{-- Filtro de Estado --}}
                    <select name="estado" class="form-control" style="min-width: 150px;" onchange="this.form.submit()">
                        <option value="">Todos los Estados</option>
                        <option value="{{ \App\Models\Producto::PENDIENTE }}" @selected(request('estado')==\App\Models\Producto::PENDIENTE)>Pendiente</option>
                        <option value="{{ \App\Models\Producto::EN_CURSO }}" @selected(request('estado')==\App\Models\Producto::EN_CURSO)>En Curso</option>
                        <option value="{{ \App\Models\Producto::OBSERVADO }}" @selected(request('estado')==\App\Models\Producto::OBSERVADO)>Observado</option>
                        <option value="{{ \App\Models\Producto::FINALIZADO }}" @selected(request('estado')==\App\Models\Producto::FINALIZADO)>Finalizado</option>
                    </select>

                    <div class="d-flex gap-2 flex-shrink-0">
                        {{-- Selector de Paginación --}}
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(request('per_page',10)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                        </button>

                        @if(request()->filled('q') || request()->filled('estado'))
                        <a href="{{ route('producto.index') }}" class="btn btn-outline-danger" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('producto.create') }}" class="btn btn-primary ml-md-2">
                    Nuevo
                </a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código Tramite</th>
                        <th>Producto</th>
                        <th>Empresa</th>
                        <th>Subcategoria</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $p)
                    <tr>
                        <td>{{ $p->codigo_tramite }}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->clienteEmpresa->nombre }}</td>
                        <td>{{ $p->subcategoria->nombre }}</td>
                        <td>
                            {{-- Dropdown de Estados (Optimizado) --}}
                            <div class="dropdown">
                                <span class="badge dropdown-toggle" data-bs-toggle="dropdown" style="background-color: {{ $p->estado_color }}; color: white; cursor: pointer;">
                                    {{ $p->estado_nombre }}
                                </span>
                                <div class="dropdown-menu">
                                    @foreach([\App\Models\Producto::EN_CURSO => 'En Curso', \App\Models\Producto::OBSERVADO => 'Observado', \App\Models\Producto::FINALIZADO => 'Finalizado'] as $val => $label)
                                    <form action="{{ route('producto.cambiarEstado', $p->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="nuevo_estado" value="{{ $val }}">
                                        <button type="submit" class="dropdown-item">{{ $label }}</button>
                                    </form>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- BOTÓN PARA ABRIR MODAL DE SUBIDA --}}
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}" title="Subir Documento">
                                    <i class="fa-solid fa-cloud-arrow-up text-white"></i>
                                </button>

                                <a href="{{ route('producto.edit',$p->id) }}" class="btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square text-white"></i></a>

                                <form action="{{ route('producto.destroy',$p->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- MODAL DE SUBIDA PARA CADA PRODUCTO --}}
                            <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">Subir Documento: {{ $p->nombre }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form id="uploadForm{{ $p->id }}" action="{{ route('documento.subir', $p->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                {{-- Barra de Progreso --}}
                                                <div class="progress mb-3 d-none" id="progressBarContainer{{ $p->id }}">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar{{ $p->id }}" role="progressbar" style="width: 0%">0%</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Archivo (PDF, Imágenes, etc. Máx 20MB)</label>
                                                    <input type="file" class="form-control" name="documento" required>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Plazo de Entrega</label>
                                                        <input type="date" name="fecha_plazo_entrega" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Fecha de Recojo</label>
                                                        <input type="date" name="fecha_recojo" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary" id="uploadButton{{ $p->id }}">Iniciar Subida</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de Subida AJAX
        document.querySelectorAll('form[id^="uploadForm"]').forEach(form => {
            const id = form.id.replace('uploadForm', '');
            const progressBarContainer = document.getElementById('progressBarContainer' + id);
            const progressBar = document.getElementById('progressBar' + id);
            const uploadButton = document.getElementById('uploadButton' + id);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);

                progressBarContainer.classList.remove('d-none');
                uploadButton.disabled = true;

                const xhr = new XMLHttpRequest();

                // Progreso
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + '%';
                        progressBar.textContent = percent + '%';
                    }
                });

                // Respuesta
                xhr.onload = function() {
                    const alertsContainer = document.getElementById('main-alerts');
                    const modalElement = document.getElementById('uploadModal' + id);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);

                    if (xhr.status === 200 || xhr.status === 201) {
                        let response;

                        try {
                            response = JSON.parse(xhr.responseText);
                        } catch (e) {
                            console.error('Respuesta no JSON:', xhr.responseText);
                            alert('Error inesperado del servidor');
                            return;
                        }


                        // Alerta de éxito
                        alertsContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                        form.reset();
                        modalInstance.hide();
                    } else {
                        alert('Error en la subida: ' + xhr.statusText);
                    }

                    uploadButton.disabled = false;
                    progressBarContainer.classList.add('d-none');
                };

                xhr.open('POST', form.action);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.send(formData);
            });
        });

        // Auto-ocultar alertas
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush