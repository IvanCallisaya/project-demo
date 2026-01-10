<div id="sucursales-tab-content">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h4 class="mb-0">Sucursales registradas del Cliente</h4>
        <a href="{{ route('sucursal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    {{-- Eliminamos el ID del form que captaba el JS --}}
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('cliente.sucursales.index', $clienteEmpresa->id) }}" class="d-flex flex-column flex-md-row gap-2 w-100">
                    {{-- CAMBIO VITAL: Indica al controlador qué pestaña refrescar --}}
                    <input type="hidden" name="currentView" value="sucursales">

                    <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre de sucursal...">

                    <div class="d-flex gap-2 flex-shrink-0">
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(request('per_page', 10)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary flex-shrink-0">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Sucursal</th>
                            <th>Contacto Principal</th>
                            <th>Email / Teléfono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sucursales as $sucursal)
                        <tr>
                            <td>{{ $sucursal->id }}</td>
                            <td><strong>{{ $sucursal->nombre }}</strong></td>
                            <td>{{ $sucursal->nombre_contacto_principal ?? 'N/A' }}</td>
                            <td>
                                <small>
                                    <i class="fas fa-envelope text-muted"></i> {{ $sucursal->email_principal ?? 'N/A' }}<br>
                                    <i class="fas fa-phone text-muted"></i> {{ $sucursal->telefono_principal ?? 'N/A' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('sucursal.edit', $sucursal->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('sucursal.destroy', $sucursal->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar sucursal?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No se encontraron sucursales.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    {{-- Usamos la variable $sucursales para la paginación --}}
                    <div>Mostrando {{ $sucursales->firstItem() ?? 0 }} - {{ $sucursales->lastItem() ?? 0 }} de {{ $sucursales->total() }}</div>
                    <div>{{ $sucursales->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</div>