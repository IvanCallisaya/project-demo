@extends('layouts.app')

@section('title', 'Crear Nueva Subcategoría')

@section('content')
<div class="container-fluid">
    <h1>Crear Nueva Subcategoría</h1>
    <div class="card">
        <div class="card-body">
            
            <form action="{{ route('subcategoria.store') }}" method="POST">
                
                {{-- Creamos un objeto vacío para el formulario reutilizable --}}
                @php 
                    $subcategoria = new \App\Models\SubCategoria();
                @endphp
                
                {{-- Incluir el formulario reutilizable --}}
                @include('subcategoria.form', ['subcategoria' => $subcategoria, 'categorias' => $categorias])

                {{-- Botones para la acción de CREAR --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar Subcategoría</button>
                    <a href="{{ route('subcategoria.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            
            </form>

        </div>
    </div>
</div>
@endsection