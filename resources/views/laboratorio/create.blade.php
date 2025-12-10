@extends('layouts.app')

@section('title','Nuevo Laboratorio')

@section('content')
<div class="container-fluid">

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
