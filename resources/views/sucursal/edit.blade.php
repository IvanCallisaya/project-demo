@extends('layouts.app')

@section('content')
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