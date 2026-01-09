<script>
    /**
     * Usamos window.addEventListener('load') para garantizar que Vite 
     * haya cargado jQuery y Select2 antes de ejecutar la lógica.
     */
    window.addEventListener('load', function() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery no está disponible.');
            return;
        }

        $(document).ready(function() {
            // Inicialización General de Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                allowClear: true
            });

            // --- LÓGICA 1: CATEGORÍAS Y SUBCATEGORÍAS ---
            const $catSelect = $('#categoria_selector');
            const $subSelect = $('#subcategoria_id');

            function actualizarSubcategorias(categoriaId, seleccionadaId = null) {
                $subSelect.empty().append('<option value="">Seleccione subcategoría...</option>');
                if (categoriaId) {
                    const filtradas = window.subcategoriasDisponibles.filter(sub => sub.categoria_id == categoriaId);
                    filtradas.forEach(sub => {
                        const isSelected = (seleccionadaId && sub.id == seleccionadaId) ? 'selected' : '';
                        $subSelect.append(`<option value="${sub.id}" ${isSelected}>${sub.nombre}</option>`);
                    });
                }
                $subSelect.trigger('change.select2');
            }

            $catSelect.on('change', function() {
                actualizarSubcategorias($(this).val());
            });

            // --- LÓGICA 2: ENCARGADO Y SUCURSAL ---
            const $encargadoSelect = $('#encargado_selector');
            const $sucursalSelect = $('#sucursal_id');

            function actualizarSucursales(clienteId, seleccionadaId = null) {
                $sucursalSelect.empty().append('<option value="">Seleccione una Sucursal...</option>');
                if (clienteId) {
                    const filtradas = window.sucursalesDisponibles.filter(suc => suc.cliente_empresa_id == clienteId);
                    filtradas.forEach(suc => {
                        const isSelected = (seleccionadaId && suc.id == seleccionadaId) ? 'selected' : '';
                        $sucursalSelect.append(`<option value="${suc.id}" ${isSelected}>${suc.nombre}</option>`);
                    });
                }
                $sucursalSelect.trigger('change.select2');
            }

            $encargadoSelect.on('change', function() {
                actualizarSucursales($(this).val());
            });

            // --- INICIALIZACIÓN AUTOMÁTICA (MODO EDICIÓN) ---

            // Cargar subcategorías si ya hay categoría
            if ($catSelect.val()) {
                actualizarSubcategorias($catSelect.val(), window.subcategoriaSeleccionada);
            }

            // Cargar sucursales si ya hay encargado
            if ($encargadoSelect.val()) {
                actualizarSucursales($encargadoSelect.val(), window.sucursalSeleccionada);
            }
        });
    });
</script>