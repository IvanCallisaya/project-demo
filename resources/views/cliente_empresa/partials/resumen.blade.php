<h4 class="mb-3">Datos Generales del Cliente</h4>
<div class="row">
    <div class="col-md-6">
        <table class="table table-sm table-borderless">
            <tr><th>Nombre Legal</th><td>{{ $clienteEmpresa->nombre }}</td></tr>
            <tr><th>Dirección</th><td>{{ $clienteEmpresa->direccion }}</td></tr>
            <tr><th>ID Empresa</th><td>{{ $clienteEmpresa->empresa_id ?? 'N/A' }}</td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-sm table-borderless">
            <tr><th>Contacto Principal</th><td>{{ $clienteEmpresa->nombre_contacto_principal }}</td></tr>
            <tr><th>Teléfono</th><td>{{ $clienteEmpresa->telefono_principal }}</td></tr>
            <tr><th>Email</th><td>{{ $clienteEmpresa->email_principal }}</td></tr>
        </table>
    </div>
</div>

<h4 class="mb-3">Lista de Contactos</h4>
{{-- Aquí iría el formulario para crear un nuevo contacto y la tabla de listado --}}
@if($clienteEmpresa->contactos->count())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clienteEmpresa->contactos as $contacto)
                <tr>
                    <td>{{ $contacto->nombre }}</td>
                    <td>{{ $contacto->telefono ?? 'N/A' }}</td>
                    <td>{{ $contacto->email ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning">No hay contactos registrados para esta empresa.</div>
@endif