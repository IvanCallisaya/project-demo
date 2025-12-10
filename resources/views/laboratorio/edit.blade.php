@extends('layouts.app')

@section('title','Editar Laboratorio')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3>Editar Laboratorio</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('laboratorio.update',$laboratorio->id) }}" method="POST">
                @csrf @method('PUT')
                @include('laboratorio.form', ['laboratorio'=>$laboratorio])
            </form>
        </div>
    </div>

</div>
@endsection
