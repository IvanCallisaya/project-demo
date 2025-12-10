@csrf

<div class="row">

    {{-- BLOQUE 1: Selector de Categoría (SIEMPRE VISIBLE) --}}
    <div class="col-md-6 mb-3">
        <label for="categoria_selector" class="form-label">Categoría Principal</label>
        <select name="categoria_selector" id="categoria_selector"
            class="form-control @error('categoria_selector') is-invalid @enderror" required>

            <option value="">-- Seleccionar Categoría --</option>

            {{-- Opción para crear una nueva categoría --}}
            <option value="new_categoria" @selected(old('categoria_selector')==='new_categoria' )>
                -- Crear Nueva Categoría --
            </option>

            {{-- Opciones de categorías existentes --}}
            @foreach($categorias as $cat)
            <option value="{{ $cat->id }}"
                @selected(old('categoria_selector', $subcategoria->categoria_id ?? '') == $cat->id)>
                {{ $cat->nombre }} ({{ $cat->codigo ?? 'N/A' }})
            </option>
            @endforeach
        </select>
        @error('categoria_selector')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    {{-- Espacio vacío en la primera fila (o úsalo para un campo no condicional) --}}
    <div class="col-md-6 mb-3">
        </div>

</div>

{{-- BLOQUE 2: Campos Condicionales de NUEVA CATEGORÍA (MUESTRA PRIMERO) --}}
<div id="new-categoria-fields"
    class="col-md-12" 
    style="display: {{ old('categoria_selector') === 'new_categoria' ? 'block' : 'none' }};">

    <div class="row mt-3 mb-3 border p-3 rounded bg-light"> 
        <div class="col-md-12 mb-2">
            <h4>Detalles de la Nueva Categoría Principal</h4>
            <hr class="mt-0">
        </div>

        <div class="col-md-6 mb-3">
            <label for="new_categoria_nombre" class="form-label">Nombre Nueva Categoría</label>
            <input type="text" name="new_categoria_nombre" id="new_categoria_nombre"
                class="form-control @error('new_categoria_nombre') is-invalid @enderror"
                value="{{ old('new_categoria_nombre') }}">
            @error('new_categoria_nombre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="new_categoria_codigo" class="form-label">Código Nueva Categoría</label>
            <input type="text" name="new_categoria_codigo" id="new_categoria_codigo"
                class="form-control @error('new_categoria_codigo') is-invalid @enderror"
                value="{{ old('new_categoria_codigo') }}">
            @error('new_categoria_codigo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    {{-- BLOQUE 3: Campos de SUB-CATEGORÍA (MUESTRA ÚLTIMO) --}}
    <div class="col-md-12 mb-2 mt-4">
        <h4>Detalles de la Subcategoría</h4>
        <hr class="mt-0">
    </div>

    {{-- Campo de Nombre de Subcategoría --}}
    <div class="col-md-6 mb-3">
        <label for="nombre" class="form-label">Nombre de la Subcategoría</label>
        <input type="text" name="nombre" id="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $subcategoria->nombre ?? '') }}" required>
        @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Campo Código de Subcategoría --}}
    <div class="col-md-6 mb-3">
        <label for="codigo" class="form-label">Código de Subcategoría</label>
        <input type="text" name="codigo" id="codigo"
            class="form-control @error('codigo') is-invalid @enderror"
            value="{{ old('codigo', $subcategoria->codigo ?? '') }}">
        @error('codigo')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selector = document.getElementById('categoria_selector');
        const newFields = document.getElementById('new-categoria-fields');

        function toggleNewCategoryFields() {
            if (selector.value === 'new_categoria') {
                newFields.style.display = 'block'; 
            } else {
                newFields.style.display = 'none';
            }
        }

        toggleNewCategoryFields();
        selector.addEventListener('change', toggleNewCategoryFields);
    });
</script>