<div class="row">
    
    {{-- CARD PRINCIPAL: DATOS GENERALES --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-building me-2"></i> Datos Generales del Cliente</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    {{-- Columna 1: IMAGEN / LOGO --}}
                    <div class="col-md-3 text-center mb-3 mb-md-0 d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3 border rounded-lg shadow-sm" style="width: 150px; height: 150px;">
                            @if($clienteEmpresa->imagen)
                            <img src="{{ asset('storage/'.$clienteEmpresa->imagen) }}"
                                class="img-fluid rounded"
                                style="width: 100%; height: 100%; object-fit:cover;"
                                alt="Logo de {{ $clienteEmpresa->nombre }}">
                            @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Columna 2: INFORMACIÓN LEGAL --}}
                    <div class="col-md-4 border-end">
                        <h5 class="text-secondary"><i class="fas fa-file-alt me-1"></i> Información Legal</h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5 fw-bold">Nombre:</dt>
                            <dd class="col-sm-7">{{ $clienteEmpresa->nombre }}</dd>

                            <dt class="col-sm-5 fw-bold">Dirección:</dt>
                            <dd class="col-sm-7">{{ $clienteEmpresa->direccion }}</dd>

                            <dt class="col-sm-5 fw-bold">NIT:</dt>
                            <dd class="col-sm-7">{{ $clienteEmpresa->nit }}</dd>

                            <dt class="col-sm-5 fw-bold">Actividad Principal:</dt>
                            <dd class="col-sm-7">{{ $clienteEmpresa->actividad_principal }}</dd>

                            <dt class="col-sm-5 fw-bold">Id Padron:</dt>
                            <dd class="col-sm-7">{{ $clienteEmpresa->id_padron }}</dd>

                            {{-- Puedes añadir más campos legales aquí si existen (ej: NIT) --}}
                        </dl>
                    </div>

                    {{-- Columna 3: INFORMACIÓN DE CONTACTO PRINCIPAL --}}
                    <div class="col-md-5">
                        <h5 class="text-secondary"><i class="fas fa-headset me-1"></i> Contacto Principal</h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-4 fw-bold">Contacto:</dt>
                            <dd class="col-sm-8">{{ $clienteEmpresa->nombre_contacto_principal }}</dd>

                            <dt class="col-sm-4 fw-bold">Teléfono:</dt>
                            <dd class="col-sm-8">
                                <a href="tel:{{ $clienteEmpresa->telefono_principal }}" class="text-decoration-none">
                                    <i class="fas fa-phone-alt me-1"></i> {{ $clienteEmpresa->telefono_principal }}
                                </a>
                            </dd>

                            <dt class="col-sm-4 fw-bold">Email:</dt>
                            <dd class="col-sm-8">
                                <a href="mailto:{{ $clienteEmpresa->email_principal }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i> {{ $clienteEmpresa->email_principal }}
                                </a>
                            </dd>
                        </dl>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    {{-- CARD SECUNDARIA: LISTA DE CONTACTOS --}}
    <div class="col-12 mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Lista de Contactos Adicionales</h5>
            </div>
            <div class="card-body p-0">
                
                @if($clienteEmpresa->contactos->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 40%;">Nombre</th>
                                <th style="width: 30%;">Teléfono</th>
                                <th style="width: 30%;">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clienteEmpresa->contactos as $contacto)
                            <tr>
                                <td><i class="fas fa-user me-2"></i> {{ $contacto->nombre }}</td>
                                <td>
                                    @if($contacto->telefono)
                                    <a href="tel:{{ $contacto->telefono }}" class="text-decoration-none">
                                        <i class="fas fa-phone-alt me-1 text-primary"></i> {{ $contacto->telefono }}
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contacto->email)
                                    <a href="mailto:{{ $contacto->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1 text-primary"></i> {{ $contacto->email }}
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-warning m-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> No hay contactos adicionales registrados para esta empresa.
                </div>
                @endif
                
            </div>
            
            {{-- Espacio para botón 'Añadir Contacto' si es necesario --}}
            {{-- <div class="card-footer text-end">
                <a href="..." class="btn btn-success"><i class="fas fa-plus me-1"></i> Añadir Contacto</a>
            </div> --}}
            
        </div>
    </div>
    
</div>