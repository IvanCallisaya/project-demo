@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <div class="card">
        <div class="card-body">
            Bienvenido, {{ auth()->user()->name }}.
        </div>
    </div>
</div>
@endsection