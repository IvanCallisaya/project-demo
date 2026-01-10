<div id="productos-tab-content">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h4 class="mb-0">Productos del Cliente</h4>
        <a href="{{ route('presolicitud.create', ['cliente_id' => $clienteEmpresa->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('cliente.productos.index', $clienteEmpresa->id) }}" class="d-flex flex-column flex-md-row gap-2 w-100">
                <input type="hidden" name="currentView" value="productos">

                <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                    placeholder="Buscar por nombre, código o id de presolicitud...">
                <select name="estado" class="form-control" style="width: auto;" onchange="this.form.submit()">
                    <option value="">Todos los Estados</option>
                    @php
                    // Creamos un mapa de constantes a nombres
                    $estados = [
                    \App\Models\Producto::SOLICITADO => 'Solicitado',
                    \App\Models\Producto::APROBADO => 'Aprobado',
                    \App\Models\Producto::RECHAZADO => 'Rechazado',
                    \App\Models\Producto::PENDIENTE => 'Pendiente',
                    \App\Models\Producto::EN_CURSO => 'En Curso',
                    \App\Models\Producto::OBSERVADO => 'Observado',
                    \App\Models\Producto::FINALIZADO => 'Finalizado',
                    ];
                    @endphp

                    @foreach($estados as $id => $nombre)
                    {{-- Ahora el value es el ID numérico --}}
                    <option value="{{ $id }}" @selected(request('estado')==$id && request('estado') !==null)>
                        {{ $nombre }}
                    </option>
                    @endforeach
                </select>

                <div class="d-flex gap-2">
                    <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                        @foreach([5,10,25,50] as $n)
                        <option value="{{ $n }}" @selected(request('per_page', 10)==$n)>{{ $n }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $prod)
                    <tr>
                        <td><span class="badge badge-light border">{{ $prod->codigo ?? 'N/A' }}</span></td>
                        <td><strong>{{ $prod->nombre }}</strong></td>
                        <td>
                            <span class="badge" style="background-color: {{ $prod->estado_color ?? '#6c757d' }}; color: white;">
                                {{ $prod->estado_nombre ?? $prod->estado }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('producto.edit', $prod->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-regular fa-pen-to-square text-white"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No se encontraron productos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="card-footer d-flex justify-content-between align-items-center">
                {{-- Usamos la variable $productos para la paginación --}}
                <div>Mostrando {{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }}</div>
                <div>{{ $productos->links() }}</div>
            </div>
        </div>


    </div>
</div>