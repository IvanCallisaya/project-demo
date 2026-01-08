<h4 class="mb-3">Laboratorios y Servicios Contratados</h4>

{{-- Aseguramos que la variable $labs contenga la colección paginada --}}
@if($sucursales->count() > 0 || request('q'))

<div class="container-fluid p-0">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                {{-- FORMULARIO DE BÚSQUEDA Y PAGINACIÓN --}}
                <form method="GET" action="{{ route('cliente.sucursales.index', $clienteEmpresa->id) }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1">
                        <input type="text" name="search" class="form-control flex-grow-1"
                            placeholder="Buscar por nombre, contacto o teléfono..."
                            value="{{ $search ?? '' }}">
                    </div>

                    <div class="d-flex gap-2 flex-shrink-0">
                        {{-- Selector de Paginación --}}
                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                                <option value="{{ $n }}" @selected(($perPage ?? 10) == $n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-secondary flex-shrink-0">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>

                {{-- BOTÓN NUEVO --}}
                <a href="{{ route('sucursal.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-md-2">
                    <i class="fas fa-plus"></i> Nuevo
                </a>
            </div>
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
                            {{-- Botón Editar --}}
                            <a href="{{ route('sucursal.edit', $sucursal->id) }}" class="btn btn-sm btn-warning text-white">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            {{-- Formulario Eliminar --}}
                            <form action="{{ route('sucursal.destroy', $sucursal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta sucursal?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No se encontraron sucursales.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            

        </div>
    </div>
</div>
@else
<div class="alert alert-info">Este cliente no tiene sucursales creadas.</div>
@endif