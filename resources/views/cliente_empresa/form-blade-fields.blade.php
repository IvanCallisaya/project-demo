<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" class="form-control" required>
            @error('nombre')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Imagen (opcional) - se guarda ruta</label>
            <input type="file" name="imagen" id="imagenInput" accept="image/*" class="form-control">
            @if(!empty($cliente->imagen ?? null))
            <div class="mt-2">
                <label>Imagen actual:</label><br>
                <img id="previewOld" src="{{ asset('storage/'.($cliente->imagen ?? '')) }}" width="120" style="object-fit:cover;border-radius:4px;">
            </div>
            @endif
            <div id="previewWrapper" class="mt-2" style="display:none;">
                <label>Nueva imagen:</label><br>
                <img id="previewImg" src="#" alt="Preview" width="120" style="object-fit:cover;border-radius:4px;">
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header d-flex justify-content-between">
                <h4>Contactos</h4>

                <button type="button" id="btnAddContacto" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Agregar Contacto
                </button>
            </div>

            <div class="card-body">
                <!-- Contenedor donde se agregan los formularios -->
                <div id="contacto-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- PLANTILLA OCULTA -->
<!-- IMPORTANTE: Los names tienen {index} como placeholder -->
<div id="contacto-template" class="contacto-item d-none">
    <div class="border rounded p-3 mb-3 bg-light">

        <div class="d-flex justify-content-between">
            <h5>Contacto</h5>
            <button type="button" class="btn btn-danger btn-sm btnRemoveContacto">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <label>Nombre</label>
                <!-- Quitamos 'required' aquí. Se añade por JS al clonar. -->
                <!-- Cambiamos name="contactos[nombre][]" por name="contactos[{index}][nombre]" -->
                <input type="text" name="contactos[{index}][nombre]" class="form-control contacto-nombre">
            </div>

            <div class="col-md-4">
                <label>Email</label>
                <input type="email" name="contactos[{index}][email]" class="form-control contacto-email">
            </div>

            <div class="col-md-4">
                <label>Teléfono</label>
                <input type="text" name="contactos[{index}][telefono]" class="form-control contacto-telefono">
            </div>
        </div>
    </div>
</div>