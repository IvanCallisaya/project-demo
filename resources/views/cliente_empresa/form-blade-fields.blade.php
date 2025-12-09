<div class="container-fluid">
    
    <form id="formClienteEmpresa"
        action="{{ isset($cliente) ? route('cliente_empresa.update',$cliente->id) : route('cliente_empresa.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @if(isset($cliente))
        @method('PUT')
        @endif

        <div class="row">
            <!-- ================================
                COLUMNA IZQUIERDA
            ================================== -->
            <div class="col-md-6">

                <!-- Nombre -->
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre',$cliente->nombre ?? '') }}"
                        class="form-control" required>
                    @error('nombre')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <!-- Dirección -->
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion',$cliente->direccion ?? '') }}"
                        class="form-control">
                </div>

                <!-- Imagen -->
                <div class="form-group">
                    <label>Imagen (se guarda ruta)</label>
                    <input type="file" name="imagen" id="imagenInput" accept="image/*" class="form-control">

                    <!-- imagen actual -->
                    @if(!empty($cliente->imagen ?? null))
                    <div class="mt-2">
                        <label>Actual:</label><br>
                        <img src="{{ asset('storage/'.$cliente->imagen) }}" width="130"
                            style="object-fit:cover;border-radius:6px;">
                    </div>
                    @endif

                    <!-- preview nueva -->
                    <div id="previewWrapper" class="mt-2" style="display:none;">
                        <label>Vista previa:</label><br>
                        <img id="previewImg" src="#" width="130"
                            style="object-fit:cover;border-radius:6px;">
                    </div>
                </div>

            </div>


            <div class="col-md-6">



                <div class="form-group">
                    <label>Nombre del contacto principal</label>
                    <input type="text" name="nombre_contacto_principal"
                        value="{{ old('nombre_contacto_principal',$cliente->nombre_contacto_principal ?? '') }}"
                        class="form-control">
                </div>

                <div class="form-group">
                    <label>Email del contacto principal</label>
                    <input type="email" name="email_principal"
                        value="{{ old('email_principal',$cliente->email_principal ?? '') }}"
                        class="form-control">
                </div>

                <div class="form-group">
                    <label>Teléfono del contacto principal</label>
                    <input type="text" name="telefono_principal"
                        value="{{ old('telefono_principal',$cliente->telefono_principal ?? '') }}"
                        class="form-control">
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