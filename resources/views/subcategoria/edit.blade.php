@extends('layouts.app')

@section('title', 'Editar Subcategoría')

@section('content')
<div class="container-fluid">
    <h1>Editar Subcategoría: {{ $subcategoria->nombre }}</h1>
    <div class="card">
        <div class="card-body">
            
            <form action="{{ route('subcategoria.update', $subcategoria->id) }}" method="POST">
                
                @method('PUT')
                
                {{-- Incluir el formulario reutilizable --}}
                @include('subcategoria.form', ['subcategoria' => $subcategoria, 'categorias' => $categorias])
                
                {{-- Botones para la acción de EDITAR --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning text-white">Actualizar</button>
                    <a href="{{ route('subcategoria.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            
            </form>

        </div>
    </div>
</div>
@endsection