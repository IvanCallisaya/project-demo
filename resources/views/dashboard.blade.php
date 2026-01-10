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
        {{-- 1. SOLICITADO - Color Cyan (#17a2b8) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow">
                <div class="inner">
                    <h3>{{ $productosSolicitado }}</h3>
                    <p>Solicitados (Pre-solicitud)</p>
                </div>
                <div class="icon"><i class="fas fa-file-import"></i></div>
                <a href="{{ route('presolicitud.index') }}" class="small-box-footer">
                    Ver Pre-solicitudes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- 2. APROBADO - Color Verde (#28a745) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow">
                <div class="inner">
                    <h3>{{ $productosAprobado }}</h3>
                    <p>Aprobados</p>
                </div>
                <div class="icon"><i class="fas fa-thumbs-up"></i></div>
                <a href="{{ route('presolicitud.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- 3. OBSERVADO - Color Naranja (#ff851b) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow">
                <div class="inner">
                    <h3 class="text-white">{{ $productosObservado }}</h3>
                    <p class="text-white">Observados</p>
                </div>
                <div class="icon"><i class="fas fa-eye"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Ver trámites <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- 4. EN CURSO - Color Azul (#007bff) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow">
                <div class="inner">
                    <h3>{{ $productosEnCurso }}</h3>
                    <p>En Curso / Trámite</p>
                </div>
                <div class="icon"><i class="fas fa-sync-alt fa-spin"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 5. PENDIENTE - Color Gris (#6c757d) --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-secondary shadow">
                <div class="inner">
                    <h3>{{ $productosPendiente }}</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- 6. FINALIZADO - Color Oliva/Verde Oscuro (#3d9970) --}}
        <div class="col-lg-4 col-6">
            <div class="small-box shadow" style="background-color: #3d9970; color: white;">
                <div class="inner">
                    <h3>{{ $productosFinalizado }}</h3>
                    <p>Finalizados</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
                <a href="{{ route('producto.index') }}" class="small-box-footer">
                    Ver Reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- 7. RECHAZADO - Color Rojo (#dc3545) --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger shadow">
                <div class="inner">
                    <h3>{{ $productosRechazado }}</h3>
                    <p>Rechazados</p>
                </div>
                <div class="icon"><i class="fas fa-ban"></i></div>
                <a href="{{ route('presolicitud.index') }}" class="small-box-footer">
                    Más detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

</div>

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div id="eventDetailContent">
                    <p><strong>Título:</strong> <span id="modalTitle"></span></p>
                    <p><strong>Fecha:</strong> <span id="modalDate"></span></p>
                    <hr>
                    <div id="additionalDetails"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="modalUrl" class="btn btn-primary" target="_blank" style="display: none;">Ver más detalles</a>
            </div>
        </div>
    </div>
</div>

@endsection



@push('scripts')

{{-- ======================= --}}
{{-- FULLCALENDAR (por CDN) --}}
{{-- ======================= --}}



<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const calendarEl = document.getElementById("calendar");
        if (!calendarEl) return;

        // Combinamos las dos fuentes de datos de Laravel
        const docs = @json($documentoEventos ?? []);
        const prods = @json($eventos ?? []);
        const todosLosEventos = [...docs, ...prods];

        const calendar = new FullCalendar.Calendar(calendarEl, {
            // Con la versión global, ya no necesitas la propiedad 'plugins: [...]'
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            // Traducción de botones
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                list: 'Agenda'
            },

            // Pasamos el array combinado
            events: todosLosEventos,

            eventClick: function(info) {
                info.jsEvent.preventDefault();

                const title = info.event.title;
                const date = info.event.start.toLocaleDateString();
                const props = info.event.extendedProps;

                // Rellenar campos básicos
                document.getElementById('modalTitle').innerText = title;
                document.getElementById('modalDate').innerText = date;

                // Construir HTML de detalles adicionales
                let extraHtml = `
        <p><strong><i class="fas fa-tag"></i> Categoría:</strong> ${props.tipo || 'N/A'}</p>
        <p><strong><i class="fas fa-building"></i> Empresa:</strong> <span class="text-primary">${props.empresa || 'N/A'}</span></p>
        <p><strong><i class="fas fa-box"></i> Producto Relacionado:</strong> ${props.producto || 'N/A'} (${props.codigo_prod || ''})</p>
    `;

                if (props.estado) {
                    extraHtml += `<p><strong><i class="fas fa-info-circle"></i> Estado del producto:</strong> ${props.estado}</p>`;
                }

                if (props.descripcion) {
                    extraHtml += `<hr><p class="text-muted small">${props.descripcion}</p>`;
                }

                document.getElementById('additionalDetails').innerHTML = extraHtml;

                // Manejo del botón de URL (Abrir documento o ver ficha)
                const btnUrl = document.getElementById('modalUrl');
                if (info.event.url) {
                    btnUrl.href = info.event.url;
                    btnUrl.innerHTML = props.tipo === 'Documento' ? '<i class="fas fa-file-pdf"></i> Ver Documento' : 'Ver Ficha';
                    btnUrl.style.display = 'inline-block';
                } else {
                    btnUrl.style.display = 'none';
                }

                const myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                myModal.show();
            }
        });

        calendar.render();
    });
</script>

@endpush