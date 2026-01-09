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
        <h3 class="card-title">Nueva Sucursal</h3>
    </div>
    <form action="{{ route('sucursal.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('sucursal.form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar Sucursal</button>
            <a href="{{ route('sucursal.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection