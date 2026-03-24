# Plan de mejoras — Admin Panel
**Rama:** `claude/optimize-sales-module-qrzKE`

---

## Grupo A — Alto impacto en flujo de trabajo (3 tareas)

### A1. Widgets de ecommerce en el dashboard
**Archivo:** `resources/views/admin/owner/dashboard.blade.php`
**Controller:** `app/Http/Controllers/OwnerDashboardController.php`

El dashboard actual muestra métricas de citas (barbería). Añadir una sección de ecommerce encima o en paralelo:

- **Pedidos hoy / esta semana / este mes** — query sobre tabla `buys` con `created_at`
- **Pedidos pendientes** — `buys.status = 'Pendiente'`, con link directo a `/buys-admin`
- **Ingresos confirmados del mes** — suma de pedidos aprobados/completados
- **Productos con stock bajo (≤ 5)** — query a `stocks` + `clothing`, con link a cada categoría

Cada widget usa el card `s-card` ya existente. Los números clickeables navegan al módulo correspondiente.

---

### A2. Badge de stock bajo en sidebar
**Archivo:** `resources/views/layouts/inc/sidebar.blade.php`
**AppServiceProvider:** `app/Providers/AppServiceProvider.php`

En el `view()->composer('*', ...)` que ya existe, añadir el conteo de productos con stock crítico (stock ≤ 5, `manage_stock = 1`) al objeto compartido globalmente.

En el sidebar, junto al ítem "Catálogo" o "Productos", añadir:
```html
@if($lowStockCount > 0)
  <span class="nav-badge">{{ $lowStockCount }}</span>
@endif
```
CSS: pill rojo pequeño, idéntico a `stock-badge stock-out` ya existente.

La query se hace una sola vez por request a nivel de composer, con `Cache::remember('low_stock_count', 5, ...)` para no penalizar cada carga de página.

---

### A3. Alerta de stock bajo en tabla de productos
**Archivo:** `resources/views/admin/clothing/index.blade.php`
**Controller:** `app/Http/Controllers/ClothingCategoryController.php` — `indexById()`

En el card de filtros (ya tiene Estado/Mostrar/Filtrar), añadir un cuarto filtro:
```html
<select id="stock-filter" class="filter-input">
  <option value="">Todos</option>
  <option value="low">Stock bajo (≤5)</option>
  <option value="out">Sin stock</option>
</select>
```

El parámetro se pasa al AJAX URL (`?stock=low`) y en `indexById()` se añade:
```php
if ($stockFilter === 'low')  $query->havingRaw('total_stock > 0 AND total_stock <= 5');
if ($stockFilter === 'out')  $query->havingRaw('total_stock = 0');
```

---

## Grupo B — Productividad y UX (4 tareas)

### B1. Filtros persistentes en DataTables (localStorage)
**Archivo:** `resources/views/admin/clothing/index.blade.php`

Al inicializar el DataTable, leer los valores guardados en `localStorage`:
```js
var savedStatus = localStorage.getItem('dt_products_status_' + CATEGORY_ID) ?? '1';
var savedLen    = parseInt(localStorage.getItem('dt_products_len') ?? '15');
```

Guardar en cada cambio:
```js
$('#status').on('change', function() {
    localStorage.setItem('dt_products_status_' + CATEGORY_ID, $(this).val());
    ...
});
$('#recordsPerPage').on('change', function() {
    localStorage.setItem('dt_products_len', $(this).val());
    ...
});
```

Al cargar, pre-seleccionar el `<option>` correspondiente.

---

### B2. Ajuste masivo de precios (% incremento/descuento)
**Archivo:** `resources/views/admin/clothing/index.blade.php`
**Controller:** `app/Http/Controllers/ClothingCategoryController.php`

Extender el bulk toolbar existente con un botón adicional:
```html
<button class="act-btn ab-neutral" id="bulk-price-adj" title="Ajustar precios">
    <span class="material-icons">percent</span>
</button>
```

Al clickear abre un mini-modal (reutilizar el patrón `quickEditModal`):
- Campo: tipo de ajuste (Incrementar / Descontar) + porcentaje
- Preview: "X productos, precio promedio actual → precio promedio resultante"
- Guardar: `POST /clothing/bulk-price-adjust`

El controller actualiza `clothing.price` con `price * (1 ± pct/100)` redondeado a enteros.
Para productos con atributos, actualiza también cada fila en `stocks.price`.

---

### B3. Copy SKU al portapapeles
**Archivo:** `app/Http/Controllers/ClothingCategoryController.php` — `addColumn('name', ...)`

En el HTML de la columna `name`, junto al código:
```php
'<p class="text-xs text-secondary mb-0 d-flex align-items-center gap-1">
    Código: <span class="sku-code">' . e($item->code) . '</span>
    <button class="copy-sku btn-icon" data-sku="' . e($item->code) . '" title="Copiar código">
        <span class="material-icons" style="font-size:.9rem">content_copy</span>
    </button>
</p>'
```

JS (delegado sobre la tabla):
```js
$(document).on('click', '.copy-sku', function() {
    navigator.clipboard.writeText($(this).data('sku'));
    // Cambiar icono temporalmente a "check"
    var icon = $(this).find('.material-icons');
    icon.text('check');
    setTimeout(() => icon.text('content_copy'), 1500);
});
```

---

### B4. Exportación filtrada en DataTables
**Archivo:** `resources/views/admin/clothing/index.blade.php`

Actualmente los botones Excel/PDF de DataTables exportan solo las filas cargadas en la página actual. Para exportar todos los resultados del filtro activo hay dos opciones:

**Opción A (client-side, más simple):** Usar `exportOptions: { modifier: { search: 'applied', order: 'applied' } }` — exporta solo las filas visibles tras la búsqueda.

**Opción B (server-side, más completa):** Nueva ruta `GET /clothing/{id}/export?status=X&stock=Y` que devuelve un Excel via `Maatwebsite\Excel` (ya instalado, se usa en `importProducts`). Los botones de la toolbar disparan una descarga directa.

Implementar Opción A primero (cero backend), con nota de que Opción B da más filas si hay server-side pagination.

---

## Grupo C — UI de pedidos (1 tarea)

### C1. Timeline visual de estado en detalle de pedido
**Archivo:** `resources/views/admin/buys/indexDetail.blade.php`

El header del pedido (líneas 27-71) muestra solo un pill de estado. Reemplazar con un stepper horizontal de 4 pasos:

```
● Pendiente  ──  ○ Aprobado  ──  ○ En preparación  ──  ○ Completado
```

- Paso activo: círculo azul relleno + label bold
- Pasos completados: círculo verde con check + línea verde
- Pasos futuros: círculo gris claro + label gris
- "Cancelado" muestra el stepper congelado en el paso donde se canceló + pill rojo encima

CSS puro, sin librería externa. Se determina el paso activo según `$buy->status`.

Mapping:
- `Pendiente` → paso 1
- `Aprobado` → paso 2
- (si existe estado intermedio como "En preparación") → paso 3
- `Completado` → paso 4

---

## Grupo D — Formularios (2 tareas)

### D1. Preview de imagen en edición de producto
**Archivo:** `resources/views/admin/clothing/edit.blade.php`

Al cargar la página, mostrar thumbnails de las imágenes actuales del producto (query a `product_images` ya disponible en el controller `edit()`).

Al seleccionar un nuevo archivo en el `<input type="file">`, mostrar preview inmediato con `FileReader`:
```js
input.addEventListener('change', function() {
    const reader = new FileReader();
    reader.onload = e => preview.src = e.target.result;
    reader.readAsDataURL(this.files[0]);
});
```

Añadir botón "Eliminar imagen" por thumbnail existente (ruta ya existe en el proyecto si hay `delete-image` route, si no: añadirla).

---

### D2. Confirmación de salida sin guardar
**Archivos:** `resources/views/admin/clothing/edit.blade.php`, `resources/views/admin/categories/edit.blade.php`

Detectar cambios en el formulario con un flag `isDirty`:
```js
var isDirty = false;
$('form input, form textarea, form select').on('change input', () => isDirty = true);
$('form').on('submit', () => isDirty = false);
window.addEventListener('beforeunload', e => {
    if (isDirty) { e.preventDefault(); e.returnValue = ''; }
});
```

No requiere dependencias externas. Aplica solo a los formularios de edición, no a los de creación (donde perder datos es menos crítico).

---

## Grupo E — Nice-to-have (1 tarea)

### E1. Buscador global (Cmd+K / Ctrl+K)
**Archivos nuevos:**
- `resources/views/layouts/inc/global-search.blade.php` (modal HTML)
- `public/js/global-search.js`
**Layout:** `resources/views/layouts/admin.blade.php` — `@include` del modal y el script

El buscador consulta un endpoint `GET /admin/search?q=X` que busca en paralelo:
- Productos (`clothing.name LIKE %q%`) — devuelve id, nombre, código, categoría
- Pedidos (`buys.id = q` o `buys.id LIKE %q%`) — devuelve id, cliente, monto, estado
- Categorías (`categories.name LIKE %q%`)

Resultados agrupados por tipo con íconos. Click navega directamente. Teclado: ↑↓ para navegar, Enter para abrir, Esc para cerrar.

Atajo de teclado:
```js
document.addEventListener('keydown', e => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        openGlobalSearch();
    }
});
```

---

## Orden de implementación sugerido

| # | Tarea | Archivos tocados | Dificultad |
|---|-------|-----------------|------------|
| 1 | A2 — Badge stock bajo sidebar | AppServiceProvider + sidebar | Baja |
| 2 | A3 — Filtro stock en tabla productos | ClothingCategoryController + clothing/index | Baja |
| 3 | B3 — Copy SKU | ClothingCategoryController | Mínima |
| 4 | B1 — Filtros persistentes | clothing/index JS | Mínima |
| 5 | B4 — Exportación filtrada (opción A) | clothing/index JS | Mínima |
| 6 | C1 — Timeline pedido | buys/indexDetail | Media |
| 7 | D1 — Preview imagen edición | clothing/edit | Media |
| 8 | D2 — Confirmación salida | clothing/edit + categories/edit | Baja |
| 9 | B2 — Ajuste masivo precios | ClothingCategoryController + clothing/index | Media |
| 10 | A1 — Widgets dashboard | OwnerDashboardController + dashboard | Media |
| 11 | E1 — Buscador global | Nuevo endpoint + layout + JS | Alta |
