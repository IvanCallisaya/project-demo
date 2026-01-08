<script>
/**
 * Usamos window.addEventListener('load') para garantizar que Vite 
 * haya cargado jQuery y Select2 antes de ejecutar la lógica.
 */
window.addEventListener('load', function() {
    
    // Verificación de existencia de jQuery
    if (typeof jQuery === 'undefined') {
        console.error('jQuery no está disponible. Verifique la carga de scripts en app.blade.php');
        return;
    }

    $(document).ready(function() {
        const $catSelect = $('#categoria_selector');
        const $subSelect = $('#subcategoria_id');

        /**
         * Función: actualizarSubcategorias
         * @param {number|string} categoriaId - ID de la categoría seleccionada
         * @param {number|string} seleccionadaId - ID de la subcategoría que debe marcarse como elegida
         */
        function actualizarSubcategorias(categoriaId, seleccionadaId = null) {
            // 1. Limpiar el select de subcategorías (dejando solo el placeholder)
            $subSelect.empty().append('<option value="">Seleccione subcategoría...</option>');

            if (categoriaId) {
                // 2. Filtrar el array global de subcategorías (pasado desde PHP a window.subcategoriasDisponibles)
                const filtradas = window.subcategoriasDisponibles.filter(function(sub) {
                    return sub.categoria_id == categoriaId;
                });

                // 3. Construir e insertar las nuevas opciones
                filtradas.forEach(function(sub) {
                    const isSelected = (seleccionadaId && sub.id == seleccionadaId) ? 'selected' : '';
                    const nuevaOpcion = `<option value="${sub.id}" ${isSelected}>${sub.nombre}</option>`;
                    $subSelect.append(nuevaOpcion);
                });
            }

            // 4. Refrescar Select2 para que reconozca los nuevos elementos
            $subSelect.trigger('change.select2');
        }

        // Inicialización de componentes Select2 con tema Bootstrap 4
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true
        });

        // Evento: Al cambiar la categoría principal
        $catSelect.on('change', function() {
            const categoriaId = $(this).val();
            actualizarSubcategorias(categoriaId);
        });

        // Lógica para modo EDICIÓN o Errores de Validación (Old Input)
        const categoriaInicial = $catSelect.val();
        if (categoriaInicial) {
            // window.subcategoriaSeleccionada debe definirse en el form.blade.php
            const subIdParaSeleccionar = window.subcategoriaSeleccionada || null;
            actualizarSubcategorias(categoriaInicial, subIdParaSeleccionar);
        }
        
    });
});
</script>