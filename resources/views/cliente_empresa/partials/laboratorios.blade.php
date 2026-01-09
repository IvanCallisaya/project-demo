<h4 class="mb-3">Laboratorios registrados</h4>
<a href="{{ route('laboratorio.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-2">
    Nuevo
</a>
{{-- Aseguramos que la variable $labs contenga la colección paginada --}}
@if($labs->count() > 0 || request('q'))

<div class="container-fluid p-0">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                {{-- LA RUTA DE BÚSQUEDA DEBE APUNTAR A LA MISMA PESTAÑA --}}
                <form id="laboratorio-search-form" method="GET" action="{{ route('cliente.laboratorios.index', $clienteEmpresa->id) }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre..."
                        value="{{ request('q') }}">

                    <div class="d-flex gap-2 flex-shrink-0">

                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(request('per_page',10)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-secondary flex-shrink-0 ml-2">Buscar</button>
                    </div>
                </form>


            </div>
        </div>

        <div class="card-body table-responsive" id="laboratorios-list-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>País</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Iteramos sobre la colección paginada $labs --}}
                    @foreach($labs as $lab)
                    <tr>
                        <td>{{ $lab->nombre }}</td>
                        {{-- Usamos la propiedad generada por withCount --}}
                        <td>{{ $lab->pais }}</td>
                        <td>{{ $lab->telefono }}</td>
                        <td style="white-space:nowrap">
                            <!-- <a href="{{ route('laboratorio.show',$lab->id) }}" class="btn btn-sm btn-info"><i class="fa-regular fa-eye" style="color:white"></i></a> -->
                            <a href="{{ route('laboratorio.edit',$lab->id) }}" class="btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square" style="color: white;"></i></a>

                            {{-- ... Formulario de eliminar ... --}}
                            <form action="{{ route('laboratorio.destroy',$lab->id) }}"
                                method="POST"
                                style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar Laboratorio?')">
                                    <i class="fa-solid fa-trash-can" style="color:white"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            {{-- Usamos la variable $labs para la paginación --}}
            <div>Mostrando {{ $labs->firstItem() ?? 0 }} - {{ $labs->lastItem() ?? 0 }} de {{ $labs->total() }}</div>
            <div>{{ $labs->links() }}</div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">Este cliente no tiene laboratorios asociados o convenios registrados.</div>
@endif