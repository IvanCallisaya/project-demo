{{--
    Vista: productos/edit.blade.php
    Variables esperadas: $producto (Objeto Producto), $subcategorias (Collection)
--}}
@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid">
    <h1>Editar Producto: {{ $producto->nombre }}</h1>
    <div class="card">
        <div class="card-body">

            <form action="{{ route('producto.update', $producto) }}" method="POST">

                @method('PUT')

                {{-- Incluir el formulario reutilizable --}}
                @include('producto.form', ['producto' => $producto,'subcategorias' => $subcategorias, 'categorias' => $categorias, 'laboratorios' => $laboratorios])
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning text-white">Actualizar</button>
                    <a href="{{ route('producto.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('producto._scripts')
@endpush