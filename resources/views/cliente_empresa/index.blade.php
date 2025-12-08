@extends('layouts.app') {{-- tu layout AdminLTE --}}

@section('title','Clientes Empresa')

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Clientes Empresa</h3>
            <a href="{{ route('cliente_empresa.create') }}" class="btn btn-primary">Nuevo</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Contactos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $e)
                    <tr>
                        <td>
                            @if($e->imagen)
                            <img src="{{ asset('storage/'.$e->imagen) }}" alt="" width="70" height="70" style="object-fit:cover;border-radius:4px;">
                            @else
                            <small class="text-muted">Sin imagen</small>
                            @endif
                        </td>
                        <td>{{ $e->nombre }}</td>
                        <td>{{ $e->telefono }}</td>
                        <td>
                            @foreach($e->contactos as $c)
                            <div>• {{ $c->nombre }} <small class="text-muted">({{ $c->telefono }})</small></div>
                            @endforeach
                        </td>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('cliente_empresa.edit',$e->id) }}" class="btn btn-sm btn-warning">Editar</a>

                            <form action="{{ route('cliente_empresa.destroy',$e->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app') {{-- tu layout AdminLTE --}}

@section('title','Clientes Empresa')

@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Clientes Empresa</h3>
            <a href="{{ route('cliente_empresa.create') }}" class="btn btn-primary">Nuevo</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Contactos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $e)
                    <tr>
                        <td>
                            @if($e->imagen)
                            <img src="{{ asset('storage/'.$e->imagen) }}" alt="" width="70" height="70" style="object-fit:cover;border-radius:4px;">
                            @else
                            <small class="text-muted">Sin imagen</small>
                            @endif
                        </td>
                        <td>{{ $e->nombre }}</td>
                        <td>{{ $e->telefono }}</td>
                        <td>
                            @foreach($e->contactos as $c)
                            <div>• {{ $c->nombre }} <small class="text-muted">({{ $c->telefono }})</small></div>
                            @endforeach
                        </td>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('cliente_empresa.edit',$e->id) }}" class="btn btn-sm btn-warning">Editar</a>

                            <form action="{{ route('cliente_empresa.destroy',$e->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection