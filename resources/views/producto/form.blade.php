{{--
    Vista: productos/form.blade.php
    Variables esperadas: $producto (objeto Producto o new Producto()), $subcategorias (Collection)
--}}
@csrf

<div class="row">

    {{-- Campo Subcategoría --}}
    <div class="col-md-6 mb-3">
        <label for="subcategoria_id" class="form-label">Subcategoría</label>
        <select name="subcategoria_id" id="subcategoria_id" class="form-control @error('subcategoria_id') is-invalid @enderror" required>
            <option value="">Seleccione una subcategoría</option>
            @foreach($subcategorias as $subcategoria)
            <option value="{{ $subcategoria->id }}"
                {{ old('subcategoria_id', $producto->subcategoria_id ?? '') == $subcategoria->id ? 'selected' : '' }}>
                {{ $subcategoria->categoria->nombre .' | '. $subcategoria->nombre  }}
            </option>
            @endforeach
        </select>
        @error('subcategoria_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Campo Nombre --}}
    <div class="col-md-6 mb-3">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre" id="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $producto->nombre ?? '') }}" required>
        @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Campo Código --}}
    <div class="col-md-6 mb-3">
        <label for="codigo" class="form-label">Código</label>
        <input type="text" name="codigo" id="codigo"
            class="form-control @error('codigo') is-invalid @enderror"
            value="{{ old('codigo', $producto->codigo ?? '') }}">
        @error('codigo')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Campo Unidad de Medida --}}
    <div class="col-md-6 mb-3">
        <label for="unidad_medida_id" class="form-label">Unidad de medida</label>
        <select name="unidad_medida_id" class="form-control">
            @foreach ($unidades as $unidad)
            <option value="{{ $unidad->id }}" @selected($producto->unidad_medida_id == $unidad->id)>
                {{ $unidad->nombre }} ({{ $unidad->simbolo }})
            </option>
            @endforeach
        </select>
    </div>


</div>
<div class="form-group">
    <label>Descripcion</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
</div>