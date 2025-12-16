@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
<div class="container-fluid">
    <h1>Crear Nuevo Producto</h1>
    <div class="card">
        <div class="card-body">

            <form action="{{ route('producto.store') }}" method="POST">

                {{-- Crear un Producto vacío para que el formulario no falle al acceder a propiedades --}}
                @php $producto = new \App\Models\Producto(); @endphp

                {{-- Incluir el formulario reutilizable --}}
                @include('producto.form', ['producto' => $producto, 'subcategorias' => $subcategorias, 'unidades' => $unidadMedida])
                <div class="mt-4">
                    <button type="submit" class="btn btn-success text-white">Guardar</button>
                    <a href="{{ route('producto.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection