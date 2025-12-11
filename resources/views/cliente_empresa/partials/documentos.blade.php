{{-- ID del contenedor para que AJAX sepa dónde inyectar el nuevo HTML --}}
<div id="documentos-tab-content">
    <div class="container-fluid p-0">
        <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">

            <a href="{{ $clienteEmpresa->url_carpeta_drive }}"
                class="btn btn-light border shadow-sm py-2 px-4 w-100 w-md-auto d-flex align-items-center justify-content-center gap-2 mb-3 mt-3"
                style="
                    border-radius: 12px;
                    font-size: 1rem;
                    font-weight: 600;
                "
                target="_blank">

                <i class="fa-solid fa-folder-open text-warning" style="font-size: 1.4rem;"></i>

                <span style="color: #444;">Abrir Carpeta en Drive</span>
            </a>

        </div>
        <div class="card">
            <div class="card-header">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                    {{-- FORMULARIO DE BÚSQUEDA AJAX --}}
                    <form id="documentos-search-form" method="GET" action="{{ route('cliente.documentos.index', $clienteEmpresa->id) }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                        {{-- Campo oculto para la vista actual (si es necesario) --}}
                        <input type="hidden" name="currentView" value="{{ $currentView }}">

                        <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                            placeholder="Buscar por nombre de archivo..."
                            value="{{ request('q') }}">

                        <div class="d-flex gap-2 flex-shrink-0">
                            {{-- Select per_page --}}
                            <select name="per_page" class="form-control" style="width: auto;">
                                @foreach([5,10,25,50] as $n)
                                <option value="{{ $n }}" @selected(request('per_page',10)==$n)>{{ $n }}</option>
                                @endforeach
                            </select>

                            <button class="btn btn-secondary flex-shrink-0 ml-2">Buscar</button>
                        </div>
                    </form>
                    {{-- Botón Nuevo (Opcional si solo quieres ver) --}}
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha de Plazo de Entrega</th>
                            <th>Fecha de Recojo</th>
                            <th>Laboratorio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allDocuments as $doc)
                        <tr>
                            <td>{{ $doc->nombre }}</td>
                            <td>{{ $doc->fecha_plazo_entrega }}</td>
                            {{-- Asume que tienes las relaciones cargadas en el Pivot (laboratorioProducto) para acceder al producto/laboratorio --}}
                            {{-- Ejemplo: $doc->pivot->producto->nombre --}}
                            <td>{{ $doc->fecha_recojo }}</td>
                            <td>{{ $doc->laboratorioProducto->laboratorio->nombre ?? 'N/A' }}</td>
                            <td style="white-space:nowrap">
                                <a href="{{ $doc->url }}" class="btn btn-sm btn-info" title="Ver Documento" target="_blank">
                                    <i class="fa-solid fa-eye" style="color:white"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No se encontraron documentos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>Mostrando {{ $allDocuments->firstItem() ?? 0 }} - {{ $allDocuments->lastItem() ?? 0 }} de {{ $allDocuments->total() }}</div>
                    <div>{{ $allDocuments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>s