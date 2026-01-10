@extends('layouts.app')

@section('title', 'Cliente: ' . $clienteEmpresa->nombre)

@section('content')
<div class="container-fluid">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <a href="javascript:history.back()"
        class="btn btn-secondary mb-3 shadow-sm"
        style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-2"></i>
        Volver Atrás
    </a>
    <div class="card">

        {{-- BLOQUE 1: ENCABEZADO Y DATOS ESENCIALES (El mismo diseño ampliado) --}}
        {{-- ... (Contenido del card-header superior) ... --}}

        <div class="card-header border-bottom-0 bg-white p-4">
            <div class="d-flex flex-column">

                {{-- Fila Superior: Nombre y Botón --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <h1 class="display-5 fw-bold mb-3 mb-md-0" style="color: #333;">
                        {{ $clienteEmpresa->nombre }}
                    </h1>

                    <a href="{{ route('cliente_empresa.edit', $clienteEmpresa->id) }}"
                        class="btn btn-warning btn-lg shadow-sm px-4">
                        <i class="fa-regular fa-pen-to-square me-2"></i> Editar Cliente
                    </a>
                </div>

                <hr class="my-3 opacity-50">

                {{-- Fila Inferior: Datos de Contacto en fila --}}
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-solid fa-location-dot text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dirección</small>
                                <span class="fw-600">{{ $clienteEmpresa->direccion }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-solid fa-phone text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Teléfono</small>
                                <span class="fw-600">{{ $clienteEmpresa->telefono_principal }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-solid fa-user-check text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Contacto Principal</small>
                                <span class="fw-600">{{ $clienteEmpresa->nombre_contacto_principal }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================================================= --}}
        {{-- BLOQUE 2: MENÚ DE NAVEGACIÓN DINÁMICO (Ajustado a tu diseño) --}}
        {{-- ================================================= --}}
        <div class="card-header border-bottom">
            {{-- Usamos nav-pills o nav-tabs; aquí usamos nav-tabs con enlaces --}}
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">

                {{-- 1. PESTAÑA RESUMEN (Ruta principal show) --}}
                <li class="nav-item">
                    <a href="{{ route('cliente_empresa.show', $clienteEmpresa->id) }}"
                        class="nav-link @if($currentView === 'resumen') active @endif">
                        <i class="fa-solid fa-circle-info me-1"></i> Resumen
                    </a>
                </li>

                {{-- 3. PESTAÑA LABORATORIOS --}}
                <li class="nav-item">
                    <a href="{{ route('cliente.laboratorios.index', $clienteEmpresa->id) }}"
                        class="nav-link @if($currentView === 'laboratorios') active @endif">
                        <i class="fa-solid fa-flask me-1"></i> Laboratorios
                    </a>
                </li>

                {{-- 4. PESTAÑA DOCUMENTOS --}}
                <li class="nav-item">
                    <a href="{{ route('cliente.documentos.index', $clienteEmpresa->id) }}"
                        class="nav-link @if($currentView === 'documentos') active @endif">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Documentos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cliente.sucursales.index', $clienteEmpresa->id) }}"
                        class="nav-link @if($currentView === 'sucursales') active @endif">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Sucursales
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cliente.productos.index', $clienteEmpresa->id) }}"
                        class="nav-link @if($currentView === 'productos') active @endif">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Productos
                    </a>
                </li>


            </ul>
        </div>

        {{-- ================================================= --}}
        {{-- BLOQUE 3: CONTENIDO DINÁMICO (card-body) --}}
        {{-- ================================================= --}}
        <div class="card-body">

            @if($currentView === 'resumen')
            {{-- Datos del Cliente: Nombre, Dirección, etc. (Vista de Resumen) --}}
            @include('cliente_empresa.partials.resumen', ['clienteEmpresa' => $clienteEmpresa])

            @elseif($currentView === 'laboratorios')
            {{-- Listado de Laboratorios asociados al cliente --}}
            @include('cliente_empresa.partials.laboratorios', ['laboratorios' => $clienteEmpresa->laboratorios])

            @elseif($currentView === 'documentos')
            {{-- Registro de actividad --}}
            @include('cliente_empresa.partials.documentos', ['documentos' => $clienteEmpresa->id])
            @elseif($currentView === 'sucursales')
            @include('cliente_empresa.partials.sucursales', ['sucursales' => $sucursales])
            @elseif($currentView === 'productos')
            @include('cliente_empresa.partials.productos', ['productos' => $productos])
            @else
            <div class="alert alert-info">Contenido no definido para esta vista.</div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('js')

<script>
    $(document).ready(function() {

        function loadTabContent(url, containerId) {
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: function() {
                    $(containerId).css('opacity', '0.5');
                },
                success: function(response) {
                    success: function(response) {
                        $(containerId).html(response).css('opacity', '1');
                        history.pushState(null, '', url);
                    }
                },
                error: function() {
                    $(containerId).css('opacity', '1');
                    alert('Error al procesar la solicitud.');
                }
            });
        }

        // Escuchar TODOS los formularios de búsqueda (productos, sucursales, documentos, laboratorios)
        $(document).on('submit', 'form[id$="-search-form"]', function(e) {
            e.preventDefault();
            var form = $(this);
            var containerId = '#' + form.closest('[id$="-tab-content"]').attr('id');
            var url = form.attr('action') + '?' + form.serialize();
            loadTabContent(url, containerId);
        });

        // Escuchar cambios en selects de paginación o estado
        $(document).on('change', 'form[id$="-search-form"] select', function() {
            $(this).closest('form').submit();
        });

        // Escuchar clicks en paginación
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var containerId = '#' + $(this).closest('[id$="-tab-content"]').attr('id');
            loadTabContent(url, containerId);
        });
    });
</script>
@endpush