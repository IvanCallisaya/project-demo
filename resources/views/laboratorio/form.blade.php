<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>Cliente Empresa *</label>
            <select name="cliente_empresa_id" class="form-control">
                @foreach($clientes as $c)
                <option value="{{ $c->id }}"
                    {{ old('cliente_empresa_id', $laboratorio->cliente_empresa_id ?? '') == $c->id ? 'selected':'' }}>
                    {{ $c->nombre }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control"
                value="{{ old('nombre', $laboratorio->nombre ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="pais">País</label>
            <select name="pais" id="pais-select" class="form-control select2">
                <option value="">Seleccione un país...</option>
                @foreach($paises as $codigo => $nombre)
                <option value="{{ $nombre }}"
                    {{ (old('pais', $laboratorio->pais ?? '') == $nombre) ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="col-md-6">

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                value="{{ old('telefono', $laboratorio->telefono ?? '') }}">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', $laboratorio->email ?? '') }}">
        </div>


    </div>

</div>

<button class="btn btn-primary">Guardar</button>
<a href="{{ route('laboratorio.index') }}" class="btn btn-secondary">Cancelar</a>