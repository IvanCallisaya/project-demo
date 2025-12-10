@extends('layouts.app')

@section('title','Laboratorio: '.$laboratorio->nombre)

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <h3 class="card-title">{{ $laboratorio->nombre }}</h3>
                <div class="text-muted small">{{ $laboratorio->direccion }}</div>
            </div>

            <div>
                <a href="{{ route('laboratorio.edit',$laboratorio->id) }}" class="btn btn-warning btn-sm">Editar</a>
            </div>
        </div>

        <div class="card-body">
            <h5>Productos asignados</h5>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laboratorio->productos as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->unidad }}</td>
                        <td>{{ $p->precio }}</td>
                        <td>{{ $p->pivot->stock ?? 0 }}</td>
                        <td>
                            <form method="POST" action="{{ route('laboratorio.producto.detach',[$laboratorio->id,$p->id]) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Remover?')">Remover</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <h5>Asignar producto existente</h5>
            <form method="POST" action="{{ route('laboratorio.producto.attach',$laboratorio->id) }}" class="form-inline">
                @csrf
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <select name="producto_id" class="form-control select2" required style="width:300px">
                            <option value="">Seleccionar producto...</option>
                            @foreach(\App\Models\Producto::orderBy('nombre')->get() as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nombre }} ({{ $prod->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="stock" class="form-control" placeholder="stock" min="0" />
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">Asignar</button>
                    </div>
                </div>
            </form>

            <hr>

            <h5>Crear nuevo producto y asignar</h5>
            <!-- Modal trigger -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">Crear producto</button>

            <!-- Modal -->
            <div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('productos.store') }}">
                        @csrf
                        <input type="hidden" name="laboratorio_id" value="{{ $laboratorio->id }}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nuevo Producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2"><label>Código</label><input name="codigo" class="form-control"></div>
                                <div class="mb-2"><label>Nombre</label><input name="nombre" class="form-control" required></div>
                                <div class="mb-2"><label>Unidad</label><input name="unidad" class="form-control"></div>
                                <div class="mb-2"><label>Precio</label><input name="precio" class="form-control" type="number" step="0.01"></div>
                                <div class="mb-2"><label>Descripción</label><textarea name="descripcion" class="form-control"></textarea></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-primary">Crear y asignar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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