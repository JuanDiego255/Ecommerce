/* ============================================================
 * ECD Tutorial Tour
 * Requires intro.js >= 7 (loaded in admin.blade.php)
 * Auto-starts on first visit to each ECD page.
 * Floating "Tutorial" button lets the user replay at any time.
 * ============================================================ */
(function () {
    'use strict';

    const path = window.location.pathname;
    if (!path.includes('/ecd/')) return;

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    function q(sel) { return document.querySelector(sel); }

    function tip(element, title, body, pos) {
        const el = typeof element === 'string' ? q(element) : element;
        if (!el) return null;
        return {
            element: el,
            intro: '<div class="ecd-tip"><div class="ecd-tip-title">' + title + '</div><div class="ecd-tip-body">' + body + '</div></div>',
            position: pos || 'bottom',
        };
    }

    function globalTip(title, body) {
        return {
            intro: '<div class="ecd-tip"><div class="ecd-tip-title">' + title + '</div><div class="ecd-tip-body">' + body + '</div></div>',
        };
    }

    function only(arr) { return arr.filter(Boolean); }

    // ─── Tour definitions ─────────────────────────────────────────────────────────

    const TOURS = {

        dashboard: function () { return only([
            globalTip(
                '👋 Bienvenido al Expediente Digital',
                'Este módulo te permite gestionar los expedientes clínicos de tus pacientes de forma profesional y segura. Esta gira interactiva te mostrará cada sección paso a paso.<br><br><em>Podés volver a verla cuando quieras con el botón <strong>Tutorial</strong> que aparece en pantalla.</em>'
            ),
            tip('.page-header',
                '🏠 Panel de control',
                'Vista completa del estado de tu práctica: pacientes activos, sesiones registradas, citas próximas y alertas importantes, todo en un solo lugar.',
                'bottom'
            ),
            tip('.row.g-3.mb-3',
                '📊 Indicadores clave',
                'Estas tarjetas se actualizan automáticamente:<br><br>' +
                '• <strong>Pacientes activos</strong> — total registrados y nuevos del mes<br>' +
                '• <strong>Sesiones del mes</strong> — actividad clínica actual<br>' +
                '• <strong>Sesiones hoy</strong> — agenda del día<br>' +
                '• <strong>Próximas citas</strong> — en los próximos 14 días<br>' +
                '• <strong>Alertas activas</strong> — pacientes que requieren atención',
                'bottom'
            ),
            tip('#sesionesChart',
                '📈 Gráfico de sesiones',
                'Evolución de sesiones en los últimos 6 meses. Identificá tendencias, temporadas altas y el crecimiento de tu práctica de un vistazo.',
                'top'
            ),
            tip('.ph-btn-add',
                '➕ Crear nuevo paciente',
                'Presioná este botón para registrar un nuevo paciente. El proceso toma menos de 2 minutos y queda todo guardado en el expediente digital.',
                'left'
            ),
        ]); },

        pacientes_index: function () { return only([
            tip('.page-header',
                '👥 Directorio de pacientes',
                'Listado completo de pacientes registrados. Podés buscar, filtrar y acceder al expediente de cada paciente con un solo click.',
                'bottom'
            ),
            tip('#searchInput',
                '🔍 Búsqueda inteligente',
                'Buscá por nombre, cédula o teléfono. La tabla se filtra en tiempo real mientras escribís, sin necesidad de presionar Enter.',
                'right'
            ),
            tip('#filterActivo',
                '🔘 Filtro por estado',
                'Filtrá entre pacientes <strong>activos</strong> e <strong>inactivos</strong>. Un paciente inactivo conserva todo su historial pero no aparece en el flujo diario.',
                'right'
            ),
            tip('#recordsPerPage',
                '📄 Registros por página',
                'Controlá cuántos pacientes se muestran a la vez. Con muchos registros, aumentar este número facilita la navegación.',
                'right'
            ),
            tip('#pacientesTable',
                '📋 Tabla de pacientes',
                'Cada fila muestra foto, nombre completo, datos de contacto, número de sesiones y estado.<br><br>' +
                'Los botones de acción permiten:<br>' +
                '• <strong>Ver</strong> el expediente completo<br>' +
                '• <strong>Editar</strong> los datos del paciente<br>' +
                '• <strong>Eliminar</strong> el registro del sistema',
                'top'
            ),
            tip('.ph-btn-add',
                '➕ Nuevo paciente',
                'Registrá un paciente nuevo. Solo necesitás nombre y apellidos para comenzar; los datos médicos y la foto podés completarlos después.',
                'left'
            ),
        ]); },

        pacientes_crear: function () { return only([
            tip('.page-header',
                '📝 Nuevo expediente clínico',
                'Completá este formulario para crear el expediente digital del paciente. Los campos con <strong>*</strong> son obligatorios para guardar.',
                'bottom'
            ),
            tip('input[name="nombre"]',
                '✏️ Nombre y apellidos',
                'El nombre completo aparece en todos los documentos generados: sesiones, reportes y consentimientos firmados.',
                'right'
            ),
            tip('input[name="cedula"]',
                '🪪 Cédula / Identificación',
                'El número de cédula permite:<br>• Búsquedas rápidas en el directorio<br>• Autocompletar los consentimientos digitales<br>• Identificar al paciente en reportes',
                'right'
            ),
            tip('input[name="fecha_nacimiento"]',
                '🎂 Fecha de nacimiento',
                'El sistema calcula la edad automáticamente y la muestra en el expediente y en todos los reportes clínicos generados.',
                'right'
            ),
            tip('input[name="telefono"]',
                '📞 Datos de contacto',
                'Teléfono y correo electrónico del paciente. Aparecen en el expediente y se pueden usar para notificaciones.',
                'right'
            ),
            tip('.col-lg-8 .surface.p-4:last-child',
                '🏥 Información adicional',
                'Datos clínicos complementarios:<br><br>' +
                '• <strong>Grupo sanguíneo</strong> — relevante para tratamientos específicos<br>' +
                '• <strong>Notas internas</strong> — visibles solo para el equipo<br>' +
                '• <strong>Fuente de referido</strong> — para estadísticas de marketing',
                'top'
            ),
            tip('#previewImg',
                '📷 Foto de perfil',
                'La foto identifica visualmente al paciente en el directorio, en su expediente y en todas las sesiones clínicas. Hacé click en el campo de archivo debajo para cargarla.',
                'left'
            ),
            tip('.s-btn-primary',
                '💾 Guardar expediente',
                'Al guardar, el expediente queda creado y podés comenzar a registrar sesiones, adjuntar fotos, gestionar consentimientos y asignar protocolos de tratamiento.',
                'top'
            ),
        ]); },

        plantillas_index: function () { return only([
            tip('.page-header',
                '📋 Plantillas de ficha clínica',
                'Las plantillas definen los <strong>campos del formulario</strong> que completarás en cada sesión. Creá plantillas especializadas por tipo de tratamiento: facial, corporal, capilar, etc.',
                'bottom'
            ),
            tip('#searchInput',
                '🔍 Buscar plantillas',
                'Filtrá por nombre o categoría. Imprescindible cuando tenés muchas plantillas para distintos tipos de procedimientos.',
                'right'
            ),
            tip('#filterEstado',
                '🔘 Estado de la plantilla',
                'Las plantillas <strong>activas</strong> están disponibles al crear sesiones. Podés desactivar plantillas en desuso sin eliminarlas ni perder su historial.',
                'right'
            ),
            tip('#plantillasTable',
                '📊 Lista de plantillas',
                'La columna <strong>Usos</strong> indica cuántas sesiones han utilizado cada plantilla — las más usadas reflejan tus procedimientos estrella.<br><br>' +
                'Desde aquí podés editar, duplicar o activar/desactivar cada plantilla.',
                'top'
            ),
            tip('.ph-btn-add',
                '➕ Nueva plantilla',
                'Creá una plantilla con exactamente los campos que necesitás: texto libre, selección, escalas de valoración, fechas y más. Sin límite de campos.',
                'left'
            ),
        ]); },

        plantillas_crear: function () { return only([
            tip('.page-header',
                '🏗️ Constructor de plantilla',
                'Diseñá una ficha clínica a tu medida. Agregá tantos campos como necesités y configuralos según el flujo de tu consulta.',
                'bottom'
            ),
            tip('input[name="nombre"]',
                '🏷️ Nombre de la plantilla',
                'Dale un nombre descriptivo: <em>"Ficha Facial Básica"</em>, <em>"Evaluación Capilar"</em>, <em>"Protocolo Anti-edad"</em>. Aparece en el selector al crear sesiones.',
                'bottom'
            ),
            tip('input[name="categoria"]',
                '📁 Categoría',
                'Agrupá tus plantillas por tipo de tratamiento (Facial, Corporal, Capilar...) para filtrarlas fácilmente cuando tengas muchas en el sistema.',
                'bottom'
            ),
            tip('input[name="color_etiqueta"]',
                '🎨 Color de etiqueta',
                'Asigná un color para identificar visualmente esta plantilla. Muy útil cuando querés distinguir tipos de tratamiento de un vistazo en el listado.',
                'right'
            ),
            tip('input[name="descripcion"]',
                '📝 Descripción',
                'Explicá brevemente para qué paciente o situación usar esta plantilla. Esta nota solo la ve el equipo interno y ayuda a elegir la plantilla correcta.',
                'bottom'
            ),
        ]); },

        protocolos_index: function () { return only([
            tip('.page-header',
                '🗂️ Protocolos de tratamiento',
                'Los protocolos estandarizan los pasos de tus procedimientos para garantizar <strong>calidad y consistencia</strong> en cada sesión, sin importar qué especialista la realice.',
                'bottom'
            ),
            tip('#searchInput',
                '🔍 Buscar protocolos',
                'Encontrá protocolos por nombre o categoría de tratamiento.',
                'right'
            ),
            tip('#filterNivel',
                '🎯 Filtro por nivel',
                'Clasificá entre <strong>Básico</strong>, <strong>Intermedio</strong> y <strong>Avanzado</strong>. Útil para asignar procedimientos según la experiencia y certificación del especialista.',
                'right'
            ),
            tip('#protocolosTable',
                '📋 Lista de protocolos',
                'Cada protocolo muestra duración estimada, nivel de dificultad y número de pasos definidos. Hacé click en Ver para consultar el detalle completo.',
                'top'
            ),
            tip('.ph-btn-add',
                '➕ Nuevo protocolo',
                'Creá un protocolo con pasos ordenados, lista de materiales necesarios y tiempos estimados. Una vez creado estará disponible para asignarlo a sesiones clínicas.',
                'left'
            ),
        ]); },

        protocolos_crear: function () { return only([
            tip('.page-header',
                '📋 Nuevo protocolo',
                'Definí un procedimiento estándar paso a paso. Un protocolo bien documentado garantiza resultados consistentes sin importar quién realice el tratamiento.',
                'bottom'
            ),
            tip('.surface.p-4',
                '📝 Información general',
                'Completá los datos principales:<br><br>' +
                '• <strong>Nombre</strong>: identificador del protocolo<br>' +
                '• <strong>Categoría</strong>: tipo de tratamiento<br>' +
                '• <strong>Duración</strong>: tiempo estimado en minutos<br>' +
                '• <strong>Nivel</strong>: básico, intermedio o avanzado<br>' +
                '• <strong>Contraindicaciones</strong>: condiciones en las que NO aplicar',
                'bottom'
            ),
            tip('#addMaterialBtn',
                '🧴 Materiales necesarios',
                'Listá todos los productos e insumos que se necesitan para este protocolo. El especialista verá esta lista antes de comenzar la sesión para preparar el espacio de trabajo.',
                'bottom'
            ),
            tip('#addPasoBtn',
                '📌 Pasos del protocolo',
                'Agregá cada paso en orden con instrucciones detalladas y tiempo estimado. Más detalle en los pasos = mayor consistencia y mejores resultados.',
                'bottom'
            ),
            tip('textarea[name="notas_post"]',
                '📋 Notas post-tratamiento',
                'Cuidados posteriores y recomendaciones para el paciente. Se incluyen automáticamente en el reporte de sesión al finalizar el tratamiento.',
                'top'
            ),
        ]); },

        consentimientos_index: function () { return only([
            tip('.page-header',
                '✍️ Consentimientos informados',
                'Los consentimientos informados son documentos legales que tus pacientes firman antes de recibir un tratamiento. Este módulo te permite crear plantillas reutilizables con firma digital.',
                'bottom'
            ),
            tip('#consentimientosTable',
                '📄 Lista de plantillas',
                'Cada plantilla muestra:<br><br>' +
                '• <strong>Tipo</strong>: categoría del consentimiento<br>' +
                '• <strong>Versión</strong>: control de cambios del documento<br>' +
                '• <strong>Firmados</strong>: cuántos pacientes ya lo firmaron<br><br>' +
                'Podés desactivar versiones antiguas sin perder el historial de firmas ya registradas.',
                'top'
            ),
            tip('.ph-btn-add',
                '➕ Nueva plantilla',
                'Creá una plantilla con variables dinámicas que se autocompletan con los datos del paciente: {NOMBRE_PACIENTE}, {FECHA}, {CEDULA}, {TRATAMIENTO}.',
                'left'
            ),
        ]); },

        consentimientos_crear: function () { return only([
            tip('.page-header',
                '📝 Nueva plantilla de consentimiento',
                'Redactá el documento que firmarán tus pacientes antes del tratamiento. Las variables dinámicas se reemplazan automáticamente con los datos reales de cada paciente.',
                'bottom'
            ),
            tip('input[name="nombre"]',
                '🏷️ Nombre de la plantilla',
                'Identificador del documento: <em>"Consentimiento Facial"</em>, <em>"Consentimiento Invasivo"</em>, <em>"Consentimiento General"</em>. Aparece al seleccionar el consentimiento desde el expediente del paciente.',
                'bottom'
            ),
            tip('input[name="tipo"]',
                '📁 Tipo / Categoría',
                'Clasificá el consentimiento según el tipo de tratamiento. Facilita encontrar la plantilla correcta cuando hay múltiples documentos creados.',
                'bottom'
            ),
            tip('.surface.p-4:nth-of-type(2) .mb-2',
                '🔧 Variables dinámicas',
                'Usá estas etiquetas en el texto y se reemplazarán automáticamente al enviar el consentimiento:<br><br>' +
                '• <code>{NOMBRE_PACIENTE}</code> — nombre completo del paciente<br>' +
                '• <code>{FECHA}</code> — fecha de firma del documento<br>' +
                '• <code>{CEDULA}</code> — número de identificación<br>' +
                '• <code>{TRATAMIENTO}</code> — título de la sesión clínica',
                'bottom'
            ),
            tip('#contenidoEditor',
                '📃 Contenido del consentimiento',
                'Redactá el texto completo del documento. Incluí:<br><br>' +
                '• Descripción clara del tratamiento<br>' +
                '• Riesgos, beneficios y alternativas<br>' +
                '• Declaración de consentimiento libre e informado<br><br>' +
                'Usá las variables dinámicas para personalizar el documento automáticamente para cada paciente.',
                'top'
            ),
            tip('.s-btn-primary',
                '💾 Guardar plantilla',
                'Una vez guardada, la plantilla estará disponible para enviarla y solicitar la firma digital desde el expediente de cualquier paciente.',
                'top'
            ),
        ]); },
    };

    // ─── URL → tour key ───────────────────────────────────────────────────────────

    function detectTour() {
        if (path.includes('/ecd/pacientes/crear'))        return 'pacientes_crear';
        if (path.includes('/ecd/plantillas/crear'))        return 'plantillas_crear';
        if (path.includes('/ecd/protocolos/crear'))        return 'protocolos_crear';
        if (path.includes('/ecd/consentimientos/crear'))   return 'consentimientos_crear';
        if (path.includes('/ecd/dashboard'))               return 'dashboard';
        if (path.includes('/ecd/pacientes'))               return 'pacientes_index';
        if (path.includes('/ecd/plantillas'))              return 'plantillas_index';
        if (path.includes('/ecd/protocolos'))              return 'protocolos_index';
        if (path.includes('/ecd/consentimientos'))         return 'consentimientos_index';
        return null;
    }

    const tourKey = detectTour();
    if (!tourKey || !TOURS[tourKey]) return;

    // ─── Launch tour ──────────────────────────────────────────────────────────────

    function launchTour() {
        if (typeof introJs === 'undefined') return;
        var steps = TOURS[tourKey]();
        if (!steps.length) return;

        introJs().setOptions({
            steps:              steps,
            nextLabel:          'Siguiente <i class="fas fa-arrow-right ms-1"></i>',
            prevLabel:          '<i class="fas fa-arrow-left me-1"></i> Anterior',
            doneLabel:          '¡Listo! <i class="fas fa-check ms-1"></i>',
            skipLabel:          '<i class="fas fa-times"></i>',
            showProgress:       true,
            showBullets:        false,
            scrollToElement:    true,
            disableInteraction: false,
            exitOnOverlayClick: false,
            overlayOpacity:     0.4,
            tooltipClass:       'ecd-tour-tooltip',
            highlightClass:     'ecd-tour-highlight',
            progressBarAdditionalClass: 'ecd-tour-progress',
        }).oncomplete(function () {
            localStorage.setItem('ecd_tour_' + tourKey, '1');
        }).onexit(function () {
            localStorage.setItem('ecd_tour_' + tourKey, '1');
        }).start();
    }

    // ─── Floating replay button ───────────────────────────────────────────────────

    function injectReplayBtn() {
        if (document.getElementById('ecd-tour-fab')) return;
        var btn = document.createElement('button');
        btn.id        = 'ecd-tour-fab';
        btn.title     = 'Ver tutorial de esta sección';
        btn.innerHTML = '<i class="fas fa-graduation-cap"></i><span>Tutorial</span>';
        btn.addEventListener('click', launchTour);
        document.body.appendChild(btn);
    }

    // ─── Boot ─────────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        injectReplayBtn();
        if (!localStorage.getItem('ecd_tour_' + tourKey)) {
            setTimeout(launchTour, 700);
        }
    });

})();
