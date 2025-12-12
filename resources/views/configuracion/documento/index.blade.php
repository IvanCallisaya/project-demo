@extends('layouts.app')
@section('title','Documentos')
@section('content')

<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
    <div class="alert alert-danger">Error: Revise los campos.</div>
    @endif

    {{-- Título ajustado --}}
    <h1>Documentos</h1>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">

                {{-- Barra de Búsqueda y Paginación --}}
                {{-- Nota: La acción debe apuntar a la ruta del index de Documentos --}}
                <form method="GET" action="{{ route('configuracion.documento') }}" class="d-flex flex-column flex-md-row gap-2 mb-2 mb-md-0 w-100 w-md-75">

                    <input type="text" name="q" value="{{ request('q') }}" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre de documento..."
                        value="{{ request('q') }}">

                    <div class="d-flex gap-2 flex-shrink-0">

                        <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                            @foreach([5,10,25,50] as $n)
                            <option value="{{ $n }}" @selected(request('per_page',15)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-secondary flex-shrink-0 ml-2">Buscar</button>
                    </div>
                </form>

                {{-- Botón de Nuevo Documento (Adaptado, si aplica en un índice global) --}}
                {{-- <a href="{{ route('documento.create') }}" class="btn btn-primary flex-shrink-0 w-md-auto ml-2">
                <i class="fa-solid fa-upload me-1"></i> Nuevo Documento
                </a> --}}
            </div>
        </div>
        {{-- Cuerpo de la Tarjeta con la Tabla --}}
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Documento</th>
                        <th>Cliente Propietario</th>
                        <th>Laboratorio</th>
                        <th>Producto</th>
                        <th>Fecha Plazo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Iteración sobre la colección de documentos ($docs) --}}
                    @foreach($docs as $doc)
                    <tr>
                        {{-- 1. Nombre del Documento y link a Drive --}}
                        <td>
                            <i class="fa-brands fa-google-drive me-1 text-primary"></i>
                            {{ $doc->nombre }}
                        </td>

                        {{-- 2. Cliente Propietario --}}
                        <td>
                            {{-- Acceso a Cliente Propietario a través de Laboratorio (N:M) --}}
                            @php
                            // Asumiendo que Laboratorio::clienteEmpresas es la relación correcta
                            $cliente = $doc->laboratorioProducto->laboratorio->cliente->first();
                            @endphp
                            {{ $cliente->nombre ?? 'N/A' }}
                        </td>

                        {{-- 3. Laboratorio --}}
                        <td>{{ $doc->laboratorioProducto->laboratorio->nombre ?? 'N/A' }}</td>

                        {{-- 4. Producto --}}
                        <td>{{ $doc->laboratorioProducto->producto->nombre ?? 'N/A' }}</td>

                        {{-- 5. Fecha Plazo de Entrega --}}
                        <td>
                            @if ($doc->fecha_plazo_entrega)
                            {{ \Carbon\Carbon::parse($doc->fecha_plazo_entrega)->format('d/m/Y') }}
                            @else
                            N/A
                            @endif
                        </td>

                        {{-- 6. Acciones --}}
                        <td style="white-space:nowrap">
                            <a href="{{ $doc->url }}" class="btn btn-sm btn-info" title="Ver Documento" target="_blank">
                                <i class="fa-solid fa-eye" style="color:white"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pie de Tarjeta con Paginación --}}
        <div class="card-footer d-flex justify-content-between">
            <div>Mostrando {{ $docs->firstItem() ?? 0 }} - {{ $docs->lastItem() ?? 0 }} de {{ $docs->total() }}</div>
            <div>{{ $docs->links() }}</div>
        </div>
    </div>
</div>

{{-- Aquí deberías incluir el script de JavaScript para manejar la eliminación vía la API de Node.js --}}
@endsection