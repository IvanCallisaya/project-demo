{{--
    Vista: productos/edit.blade.php
    Variables esperadas: $producto (Objeto Producto), $subcategorias (Collection)
--}}
@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid">
    <a href="javascript:history.back()"
        class="btn btn-secondary mb-3 shadow-sm"
        style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-2"></i>
        Volver Atrás
    </a>
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
<div class="timeline">
    @foreach($producto->bitacoras as $bitacora)
    <div>
        <i class="fas fa-check bg-blue"></i>
        <div class="timeline-item">
            <span class="time"><i class="fas fa-clock"></i> {{ $bitacora->created_at->format('d/m/Y H:i') }}</span>
            <h3 class="timeline-header"><strong>{{ $bitacora->evento }}</strong></h3>
            <div class="timeline-body">
                De: <span class="badge badge-secondary">{{ $bitacora->estado_anterior ?? 'N/A' }}</span>
                A: <span class="badge badge-primary">{{ $bitacora->estado_nuevo }}</span>
                <p class="mt-2 text-muted">{{ $bitacora->observacion }}</p>
                <small>Realizado por: {{ $bitacora->usuario->name }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
@include('producto._scripts')
@endpush