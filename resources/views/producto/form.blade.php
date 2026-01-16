@csrf
<script>
    window.subcategoriasDisponibles = @json($subcategorias->map(function($sub) {
        return ['id' => $sub->id, 'nombre' => $sub->nombre, 'categoria_id' => $sub->categoria_id];
    }));
    window.subcategoriaSeleccionada = "{{ old('subcategoria_id', $producto->subcategoria_id ?? '') }}";
</script>

<div class="row">
    {{-- Pre-solicitud: Se desactiva en EDIT --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Pre-solicitud de tramite</label>
        @if($producto->exists)
            <input type="text" class="form-control" value="{{ $producto->id_presolicitud }} - {{ $producto->tramite }}" readonly>
            <input type="hidden" name="id_presolicitud" value="{{ $producto->id }}">
        @else
            <select name="id_presolicitud" id="id_presolicitud" class="form-control select2" required>
                <option value="">Seleccione Pre-solicitud...</option>
                @foreach($productos as $p)
                    <option value="{{ $p->id }}" @selected(old('id_presolicitud') == $p->id)>
                        {{ $p->id_presolicitud }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Categoría Principal</label>
        @php 
            $catIdActual = old('categoria_id', $producto->subcategoria->categoria_id ?? ''); 
        @endphp
        <select id="categoria_selector" name="categoria_id" class="form-control select2">
            <option value="">Seleccione Categoría...</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" @selected($catIdActual == $cat->id)>
                    {{ $cat->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Subcategoría --}}
    <div class="col-md-6 mb-3">
        <label for="subcategoria_id" class="form-label">Subcategoría *</label>
        <select name="subcategoria_id" id="subcategoria_id" class="form-control select2" required>
            <option value="">Seleccione subcategoría...</option>
            {{-- Se llena vía JS --}}
        </select>
    </div>
    {{-- Campo Nombre --}}
    <div class="col-md-6 mb-3">
        <label for="nombre" class="form-label">Nombre del Producto *</label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $producto->nombre ?? '') }}" required>
    </div>

    {{-- Laboratorio Titular --}}
    <div class="col-md-6 mb-3">
        <label>Laboratorio Titular</label>
        <select name="laboratorio_titular_id" class="form-control select2">
            <option value="">Seleccione Laboratorio...</option>
            @foreach($laboratorios as $lab)
            <option value="{{ $lab->id }}" @selected(old('laboratorio_titular_id', $producto->laboratorio_titular_id ?? '') == $lab->id)>
                {{ $lab->nombre }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Laboratorio Producción --}}
    <div class="col-md-6 mb-3">
        <label>Laboratorio Producción</label>
        <select name="laboratorio_produccion_id" class="form-control select2">
            <option value="">Seleccione Laboratorio...</option>
            @foreach($laboratorios as $lab)
            <option value="{{ $lab->id }}" @selected(old('laboratorio_produccion_id', $producto->laboratorio_produccion_id ?? '') == $lab->id)>
                {{ $lab->nombre }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Código Trámite --}}
    <div class="col-md-6 mb-3">
        <label>Código de Trámite</label>
        <input type="text" name="codigo_tramite" class="form-control" value="{{ old('codigo_tramite', $producto->codigo_tramite ?? '') }}">
    </div>

    {{-- Código Producto --}}
    <div class="col-md-6 mb-3">
        <label>Código Producto (Interno)</label>
        <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $producto->codigo ?? '') }}">
    </div>

    {{-- Fecha Inicio --}}
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label>Fecha y Hora de Inicio *</label>
            <input type="datetime-local" name="fecha_inicio" class="form-control"
                value="{{ old('fecha_inicio', isset($producto->fecha_inicio) ? \Carbon\Carbon::parse($producto->fecha_inicio)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Fecha Registro</label>
        <input type="date" 
            name="fecha_registro" 
            class="form-control" 
            value="{{ old('fecha_registro', isset($producto->fecha_registro) ? \Carbon\Carbon::parse($producto->fecha_registro)->format('Y-m-d') : date('Y-m-d')) }}" 
            required>
    </div>
</div>