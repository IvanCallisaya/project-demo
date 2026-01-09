<div id="productos-tab-content">
    <div class="container-fluid p-0">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h4 class="mb-0">Pre-solicitud de trámites y Productos del Cliente</h4>
            <a href="{{ route('presolicitud.create', ['cliente_id' => $clienteEmpresa->id]) }}"
               class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo
            </a>
        </div>

        <div class="card">
            {{-- Filtros --}}
            <div class="card-header">
                <form id="productos-search-form"
                      method="GET"
                      action="{{ route('cliente.productos.index', $clienteEmpresa->id) }}"
                      class="d-flex flex-column flex-md-row gap-2 w-100">

                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control flex-grow-1"
                           placeholder="Buscar por nombre, código o ID pre-solicitud">

                    <select name="estado" class="form-control" style="width:auto">
                        <option value="">Todos los Estados</option>
                        <option value="{{ \App\Models\Producto::PENDIENTE }}"
                            @selected(request('estado') == \App\Models\Producto::PENDIENTE)>
                            Pendiente
                        </option>
                        <option value="{{ \App\Models\Producto::EN_CURSO }}"
                            @selected(request('estado') == \App\Models\Producto::EN_CURSO)>
                            En Curso
                        </option>
                        <option value="{{ \App\Models\Producto::OBSERVADO }}"
                            @selected(request('estado') == \App\Models\Producto::OBSERVADO)>
                            Observado
                        </option>
                        <option value="{{ \App\Models\Producto::FINALIZADO }}"
                            @selected(request('estado') == \App\Models\Producto::FINALIZADO)>
                            Finalizado
                        </option>
                    </select>

                    <select name="per_page" class="form-control" style="width:auto">
                        @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}"
                                @selected(request('per_page', 10) == $n)>
                                {{ $n }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-secondary">
                        Buscar
                    </button>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Código / Pre-solicitud</th>
                            <th>Producto</th>
                            <th>Subcategoría</th>
                            <th>Estado</th>
                            <th>Laboratorio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $prod)
                            <tr>
                                <td>
                                    @php
                                        $codigos = collect([
                                            $prod->codigo,
                                            $prod->id_presolicitud
                                        ])->filter()->unique();
                                    @endphp
                                    <span class="badge badge-light border">
                                        {{ $codigos->isEmpty() ? 'No registrado' : $codigos->implode(' / ') }}
                                    </span>
                                </td>

                                <td><strong>{{ $prod->nombre }}</strong></td>

                                <td>{{ $prod->subcategoria->nombre ?? 'N/A' }}</td>

                                <td>
                                    <span class="badge"
                                          style="background-color: {{ $prod->estado_color }}; color:white;">
                                        {{ $prod->estado_nombre }}
                                    </span>
                                </td>

                                <td>{{ $prod->laboratorioTitular->nombre ?? 'N/A' }}</td>

                                <td class="text-center" style="white-space:nowrap">
                                    <a href="{{ route('producto.edit', $prod->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fa-regular fa-pen-to-square text-white"></i>
                                    </a>

                                    <form action="{{ route('producto.destroy', $prod->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar Producto?')">
                                            <i class="fa-solid fa-trash-can text-white"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No se encontraron productos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</div>
