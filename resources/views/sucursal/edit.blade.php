@extends('layouts.app')

@section('content')
<a href="javascript:history.back()"
    class="btn btn-secondary mb-3 shadow-sm"
    style="border-radius: 8px;">
    <i class="fas fa-arrow-left me-2"></i>
    Volver Atrás
</a>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Sucursal: {{ $sucursal->nombre }}</h3>
    </div>
    <form action="{{ route('sucursal.update', $sucursal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('sucursal.form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Actualizar Sucursal</button>
            <a href="{{ route('sucursal.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection