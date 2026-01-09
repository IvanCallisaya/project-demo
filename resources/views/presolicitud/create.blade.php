@extends('layouts.app')

@section('title', 'Nueva Pre-Solicitud')

@section('content')
<div class="container-fluid">
    <h1>Nueva Pre-Solicitud</h1>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Datos Básicos Obligatorios</h3>
        </div>
        <form action="{{ route('presolicitud.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @include('presolicitud.form')
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('presolicitud.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Pre-solicitud
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
    @include('producto._scripts')
@endpush