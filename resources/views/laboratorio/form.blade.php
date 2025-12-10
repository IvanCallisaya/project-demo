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
            <label>Responsable Técnico</label>
            <input type="text" name="responsable" class="form-control"
                value="{{ old('responsable', $laboratorio->responsable ?? '') }}">
        </div>

        <div class="form-group">
            <label>Registro SENASAG</label>
            <input type="text" name="registro_senasag" class="form-control"
                value="{{ old('registro_senasag', $laboratorio->registro_senasag ?? '') }}">
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

        <div class="form-group">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control"
                value="{{ old('ciudad', $laboratorio->ciudad ?? '') }}">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control"
                value="{{ old('direccion', $laboratorio->direccion ?? '') }}">
        </div>

    </div>

</div>

<div class="form-group">
    <label>Categoría</label>
    <input type="text" name="categoria" class="form-control"
        value="{{ old('categoria', $laboratorio->categoria ?? '') }}">
</div>

<div class="form-group">
    <label>Observaciones</label>
    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $laboratorio->observaciones ?? '') }}</textarea>
</div>

<button class="btn btn-primary">Guardar</button>
<a href="{{ route('laboratorio.index') }}" class="btn btn-secondary">Cancelar</a>