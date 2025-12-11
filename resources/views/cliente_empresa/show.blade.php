@extends('layouts.app')

@section('title', 'Cliente: ' . $clienteEmpresa->nombre)

@section('content')
<div class="container-fluid">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        
        {{-- BLOQUE 1: ENCABEZADO Y DATOS ESENCIALES (El mismo diseño ampliado) --}}
        {{-- ... (Contenido del card-header superior) ... --}}
        
        <div class="card-header border-bottom-0">
             {{-- ... Contenido del encabezado (nombre, teléfono, edición) ... --}}
             {{-- (Se omite aquí por espacio, pero es el mismo que definimos antes) --}}
             
             <div class="row align-items-center">
                {{-- Contenido de info y botones de edición --}}
                <div class="col-12 col-md-9">
                    <h1 class="card-title mb-1" style="font-size: 2.25rem;">{{ $clienteEmpresa->nombre }}</h1>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <div><i class="fa-solid fa-location-dot me-1"></i> {{ $clienteEmpresa->direccion }}</div>
                        <div><i class="fa-solid fa-phone me-1"></i> {{ $clienteEmpresa->telefono_principal }}</div>
                        <div><i class="fa-solid fa-user me-1"></i> <strong>Contacto Ppal:</strong> {{ $clienteEmpresa->nombre_contacto_principal }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('cliente_empresa.edit',$clienteEmpresa->id) }}" class="btn btn-warning btn-lg w-100 w-md-auto">
                        <i class="fa-regular fa-pen-to-square me-1"></i> Editar Cliente
                    </a>
                </div>
            </div>
        </div>
        
        {{-- ================================================= --}}
        {{-- BLOQUE 2: MENÚ DE NAVEGACIÓN DINÁMICO (Ajustado a tu diseño) --}}
        {{-- ================================================= --}}
        <div class="card-header p-0 pt-3 border-bottom">
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

                 {{-- 4. PESTAÑA HISTORIAL --}}
                <li class="nav-item">
                    <a href="#" 
                       class="nav-link @if($currentView === 'historial') active @endif">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Historial
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
            
            @elseif($currentView === 'contactos')
                {{-- Listado de Contactos (Usamos la relación cargada en el controlador) --}}
                @include('cliente_empresa.partials.contactos', ['contactos' => $clienteEmpresa->contactos])

            @elseif($currentView === 'laboratorios')
                {{-- Listado de Laboratorios asociados al cliente --}}
                @include('cliente_empresa.partials.laboratorios', ['laboratorios' => $clienteEmpresa->laboratorios])
            
            @elseif($currentView === 'historial')
                {{-- Registro de actividad --}}
                @include('cliente_empresa.partials.historial')

            @else
                <div class="alert alert-info">Contenido no definido para esta vista.</div>
            @endif
        </div>
        
    </div>
</div>
@endsection

@push('js')
<script>
    function loadLaboratorios(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // 1. Encontrar el HTML del contenido de la tabla en la respuesta
                var newContent = $(response).find('#laboratorios-tab-content').html();
                
                // 2. Reemplazar solo el contenido de la pestaña
                $('#laboratorios-tab-content').html(newContent);
                
                // 3. (Opcional) Actualizar la URL en la barra de direcciones sin recargar
                history.pushState(null, null, url);
            },
            error: function(xhr) {
                alert('Error al cargar la lista de laboratorios.');
                console.error(xhr);
            }
        });
    }

    // Listener para enlaces de paginación
    $(document).on('click', '#laboratorios-tab-content .pagination a', function(e) {
        e.preventDefault();
        loadLaboratorios($(this).attr('href'));
    });

    // Listener para el formulario de búsqueda y per_page
    $(document).on('submit', '#laboratorio-search-form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action') + '?' + $(this).serialize();
        loadLaboratorios(url);
    });

    // Listener para el cambio de per_page (si no envías el formulario completo)
    // El onchange="this.form.submit()" en el select ya llama al submit,
    // que es capturado por el listener anterior. Si lo haces via JS:
    /*
    $(document).on('change', '#laboratorio-search-form select[name="per_page"]', function() {
         $(this).closest('form').submit();
    });
    */

</script>
@endpush