@extends('layouts.app')

@section('title','Nuevo Cliente Empresa')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('cliente_empresa.store') }}" enctype="multipart/form-data" id="formCliente">
        @csrf

        @include('cliente_empresa.form-blade-fields', ['cliente' => null])

        <div class="mt-3">
            <a href="{{ route('cliente_empresa.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let contactIndex = 0; // Contador global para índices únicos (0, 1, 2...)

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        const $contenedor = $("#contacto-container");
        const $plantilla = $("#contacto-template");
        let maxContactos = 5;

        function agregarContacto() {
            // Validar máximo
            let cantidadActual = $contenedor.find(".contacto-item").not($plantilla).length;
            if (cantidadActual >= maxContactos) {
                alert("Solo puedes agregar un máximo de " + maxContactos + " contactos");
                return;
            }

            // 1. Clonar plantilla
            let $nuevoContacto = $plantilla.clone();

            // 2. REEMPLAZO DE ÍNDICE (La clave para que guarde en BD)
            // Tomamos el HTML, reemplazamos {index} por el número actual (0, 1, etc)
            let htmlConIndices = $nuevoContacto.html().replace(/\{index\}/g, contactIndex);
            $nuevoContacto.html(htmlConIndices);

            // 3. Configurar visibilidad y validación
            $nuevoContacto.removeClass("d-none");
            $nuevoContacto.removeAttr("id");

            // Añadir required solo al campo visible
            $nuevoContacto.find('.contacto-nombre').attr('required', true);

            // 4. Agregar al DOM
            $contenedor.append($nuevoContacto);

            // 5. Aumentar contador para el siguiente contacto
            contactIndex++;
        }

        // Eventos
        $("#btnAddContacto").on("click", function() {
            agregarContacto();
        });

        $contenedor.on("click", ".btnRemoveContacto", function() {
            $(this).closest(".contacto-item").remove();
            // No necesitamos decrementar contactIndex, siempre debe crecer para ser único
        });

        // Inicialización: Agregar primer contacto vacío
        if ($contenedor.find(".contacto-item").not($plantilla).length === 0) {
            agregarContacto();
        }

        // Preview de Imagen
        const inputImg = document.getElementById('imagenInput');
        const previewImg = document.getElementById('previewImg');
        const previewWrapper = document.getElementById('previewWrapper');

        if (inputImg) {
            inputImg.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        previewImg.src = ev.target.result;
                        previewWrapper.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush