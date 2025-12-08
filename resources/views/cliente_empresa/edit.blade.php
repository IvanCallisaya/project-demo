@extends('layouts.app')

@section('title','Editar Cliente Empresa')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('cliente_empresa.update', $cliente_empresa->id) }}" enctype="multipart/form-data" id="formCliente">
        @csrf
        @method('PUT')

        @include('cliente_empresa.form-blade-fields', ['cliente' => $cliente_empresa])

        <div class="mt-3">
            <a href="{{ route('cliente_empresa.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar cambios</button>
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
    // This tells Blade to render the result of json_encode without escaping HTML
    const existingContacts = @json($cliente_empresa->contactos ?? []);
    let contactIndex = 0;

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        const $contenedor = $("#contacto-container");
        const $plantilla = $("#contacto-template"); // ID corregido para coincidir con form-blade-fields
        let maxContactos = 5;

        function agregarContacto(data = null) {
            let cantidadActual = $contenedor.find(".contacto-item").not($plantilla).length;
            if (cantidadActual >= maxContactos) {
                alert("Solo puedes agregar un máximo de " + maxContactos + " contactos");
                return;
            }

            let $nuevoContacto = $plantilla.clone();

            // Reemplazar {index}
            let htmlConIndices = $nuevoContacto.html().replace(/\{index\}/g, contactIndex);
            $nuevoContacto.html(htmlConIndices);

            $nuevoContacto.removeClass("d-none");
            $nuevoContacto.removeAttr("id");

            // Llenar datos si existen (Edición)
            if (data) {
                if (data.nombre) $nuevoContacto.find('.contacto-nombre').val(data.nombre);
                if (data.email) $nuevoContacto.find('.contacto-email').val(data.email);
                if (data.telefono) $nuevoContacto.find('.contacto-telefono').val(data.telefono);
            }

            // Required para validación
            $nuevoContacto.find('.contacto-nombre').attr('required', true);

            $contenedor.append($nuevoContacto);
            contactIndex++;
        }

        // Cargar contactos existentes al iniciar
        if (existingContacts.length > 0) {
            existingContacts.forEach(contact => {
                agregarContacto(contact);
            });
        } else {
            // Si no hay contactos (aunque sea edit), mostramos uno vacío opcionalmente
            // o lo dejamos vacío según prefieras. Aquí agrego uno si quieres consistencia.
            // agregarContacto(); 
        }

        $("#btnAddContacto").on("click", function() {
            agregarContacto();
        });

        $contenedor.on("click", ".btnRemoveContacto", function() {
            $(this).closest(".contacto-item").remove();
        });

        // Imagen Preview
        const inputImg = document.getElementById('imagenInput');
        const previewImg = document.getElementById('previewImg');
        const previewWrapper = document.getElementById('previewWrapper');
        const previewOld = document.getElementById('previewOld');

        if (inputImg) {
            inputImg.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    if (previewWrapper) previewWrapper.style.display = 'none';
                    if (previewOld) previewOld.style.display = 'block';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(ev) {
                    if (previewImg) previewImg.src = ev.target.result;
                    if (previewWrapper) previewWrapper.style.display = 'block';
                    if (previewOld) previewOld.style.display = 'none';
                }
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endpush