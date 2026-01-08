import "./bootstrap";

import $ from "jquery";
window.$ = window.jQuery = $;
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

// 2. Importar el Script de AdminLTE
import "admin-lte";

// 1. Importar los módulos que instalaste
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
// Necesitas timegrid y list para las vistas de semana/día/lista
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import select2 from "select2";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap4-theme/dist/select2-bootstrap4.min.css";
window.$ = window.jQuery = $;
select2();

// Configuración global para cualquier select con la clase .select2
$(document).ready(function () {
    $(".select2").select2({
        theme: "bootstrap4",
        width: "100%",
        //  placeholder: "Seleccione una opción",
        allowClear: true,
    });
});

// 2. Definición del Locale Español (si no lo importaste directamente)
import esLocale from "@fullcalendar/core/locales/es";

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");

    if (calendarEl) {
        // Asumiendo que has pasado los eventos a una variable global
        // o a un atributo data en el HTML.
        const calendarEvents = window.documentoEventos || [];

        var calendar = new Calendar(calendarEl, {
            // ¡AHORA USAS LAS VARIABLES DEL PLUGIN IMPORTADAS!
            plugins: [
                dayGridPlugin,
                timeGridPlugin,
                listPlugin,
                interactionPlugin,
            ],

            initialView: "dayGridMonth",
            // Pasa el objeto locale
            locale: esLocale,
            height: "auto",

            headerToolbar: {
                left: "prev,next today",
                center: "title",
                // Nombres de vista estándar
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
            },

            // Cargar eventos (Asegúrate de que `window.documentoEventos` se defina en tu Blade)
            events: calendarEvents,

            // Otras configuraciones...
        });

        calendar.render();
    }
});
import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
