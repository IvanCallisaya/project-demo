<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>ID Pre-solicitud *</label>
            <input type="text" name="id_presolicitud" class="form-control"
                value="{{ old('id_presolicitud', $producto->id_presolicitud ?? '') }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Fecha y Hora de Solicitud *</label>
            <input type="datetime-local" name="fecha_solicitud" class="form-control"
                value="{{ old('fecha_solicitud', isset($producto->fecha_solicitud) ? \Carbon\Carbon::parse($producto->fecha_solicitud)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tipo de Trámite *</label>
            <select name="tramite" class="form-control select2" data-placeholder="Seleccione el trámite..." required>
                <option value=""></option>
                @foreach($opcionesTramite as $opcion)
                <option value="{{ $opcion }}"
                    {{ old('tramite', $producto->tramite ?? '') == $opcion ? 'selected' : '' }}>
                    {{ $opcion }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Sucursal</label>
            <select name="sucursal_id" class="form-control select2">
                <option value="">Seleccione una Sucursal...</option>
                @foreach($sucursales as $sucursal)
                <option value="{{ $sucursal->id }}"
                    {{ (old('sucursal_id', $producto->sucursal_id ?? '') == $sucursal->id) ? 'selected' : '' }}>
                    ({{ $sucursal->clienteEmpresa->nombre ?? 'Sin asignar' }}) - {{ $sucursal->nombre }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Encargado</label>
            <select name="cliente_empresa_id" class="form-control select2">
                <option value="">Seleccione un Encargado...</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}"
                    {{ (old('cliente_empresa_id', $producto->cliente_empresa_id ?? '') == $cliente->id) ? 'selected' : '' }}>
                    {{ $cliente->nombre }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>