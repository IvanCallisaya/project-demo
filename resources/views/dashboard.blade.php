@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')

<style>
    #calendar {
        background: white;
        border-radius: 10px;
        padding: 15px;
    }

    .dashboard-title {
        font-weight: bold;
        color: #222;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #444;
    }
</style>
@endpush


@section('content')

<div class="container-fluid">

    <h4 class="dashboard-title mb-3">
        <i class="fas fa-calendar-alt"></i> Fechas de Entrega de Documentos
    </h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- CLIENTES --}}
    <div class="row justify-content-start mb-4">
        <div class="col-lg-4 col-md-6">
            <h5 class="section-title"><i class="fas fa-users"></i> Clientes</h5>

            <div class="small-box bg-info shadow">
                <div class="inner">
                    <h3>{{ $totalClientes }}</h3>
                    <p>Clientes Empresa Registradas</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('cliente_empresa.index') }}" class="small-box-footer">
                    Ver Clientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ESTADOS DE PRODUCTOS --}}
    <h5 class="section-title"><i class="fas fa-boxes"></i> Estados de Productos</h5>

    <div class="row">

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-warning shadow">
                <div class="inner">
                    <h3>$productosIniciado</h3>
                    <p>Iniciado</p>
                </div>
                <div class="icon"><i class="fas fa-hourglass-start"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-primary shadow">
                <div class="inner">
                    <h3>$productosProceso</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon"><i class="fas fa-cog"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-success shadow">
                <div class="inner">
                    <h3> $productosCompletado</h3>
                    <p>Completado</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

</div>

@endsection



@push('scripts')

{{-- ======================= --}}
{{-- FULLCALENDAR (por CDN) --}}
{{-- ======================= --}}



<script>
    document.addEventListener("DOMContentLoaded", function() {

        let el = document.getElementById("calendar");
        if (!el) return;

        let calendar = new FullCalendar.Calendar(el, {
            // ************************************************************
            // !!! CORRECCIÓN CRÍTICA: USAR NOMBRES DE STRING DE PLUGIN !!!
            // ************************************************************
            plugins: [
                'daygrid', // Correcto
                'timegrid', // Correcto
                'list' // Correcto
            ],

            initialView: "dayGridMonth",
            locale: "es",
            height: "auto",

            // Los nombres de las vistas en headerToolbar coinciden con los plugins cargados.
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
            },

            // Cargar eventos (Verifica el formato YYYY-MM-DD en el controlador)
            events: @json($documentoEventos ?? []),
            
            // Opcional: Para manejar clics en eventos
            eventClick: function(info) {
                if (info.event.url) {
                    window.open(info.event.url);
                    info.jsEvent.preventDefault();
                }
            }
        });

        calendar.render();
    });
    window.documentoEventos = @json($documentoEventos ?? []);
</script>

@endpush