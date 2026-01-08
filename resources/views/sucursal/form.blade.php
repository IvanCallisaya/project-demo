<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Cliente Empresa *</label>
            <select name="cliente_empresa_id" class="form-control select2" required>
                <option value="">Seleccione un cliente...</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->id }}" 
                        {{ (old('cliente_empresa_id', $sucursal->cliente_empresa_id ?? '') == $c->id) ? 'selected' : '' }}>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre de la Sucursal *</label>
            <input type="text" name="nombre" class="form-control" 
                value="{{ old('nombre', $sucursal->nombre ?? '') }}" required>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" 
                value="{{ old('direccion', $sucursal->direccion ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nombre Contacto Principal</label>
            <input type="text" name="nombre_contacto_principal" class="form-control" 
                value="{{ old('nombre_contacto_principal', $sucursal->nombre_contacto_principal ?? '') }}">
        </div>

        <div class="form-group">
            <label>Email Principal</label>
            <input type="email" name="email_principal" class="form-control" 
                value="{{ old('email_principal', $sucursal->email_principal ?? '') }}">
        </div>

        <div class="form-group">
            <label>Teléfono Principal</label>
            <input type="text" name="telefono_principal" class="form-control" 
                value="{{ old('telefono_principal', $sucursal->telefono_principal ?? '') }}">
        </div>
    </div>
</div>