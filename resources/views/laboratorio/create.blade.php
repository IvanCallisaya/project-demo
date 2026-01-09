@extends('layouts.app')

@section('title','Nuevo Laboratorio')

@section('content')
<div class="container-fluid">
    <a href="javascript:history.back()"
        class="btn btn-secondary mb-3 shadow-sm"
        style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-2"></i>
        Volver Atrás
    </a>
    <div class="card">
        <div class="card-header">
            <h3>Registrar Laboratorio</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('laboratorio.store') }}" method="POST">
                @csrf
                @include('laboratorio.form')
            </form>
        </div>
    </div>

</div>
@endsection