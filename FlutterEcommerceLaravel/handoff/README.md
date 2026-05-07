# Handoff — Rediseño "Oscuro Premium"
### FlutterEcommerceApp · `lib/src/presentation`

Este paquete contiene todo lo que Claude Code necesita para implementar el rediseño aprobado en el prototipo `Marketplace Redesign.html`.

**Archivos en `/handoff/`:**
- `AppTheme.dart` — `ThemeData` completo + `AppTokens` extension. Pegar tal cual en `lib/src/presentation/theme/app_theme.dart`.
- `design_tokens.json` — Tokens en JSON (referencia legible).
- `README.md` — Este archivo. Pasos en orden.

---

## 0 · Setup inicial

### Dependencias en `pubspec.yaml`
```yaml
dependencies:
  google_fonts: ^6.2.1   # ya viene en proyectos modernos; verificar
```

### Wireup en `main.dart` (o donde construyas `MaterialApp`)
```dart
import 'package:flutter/material.dart';
import 'src/presentation/theme/app_theme.dart';

MaterialApp(
  theme: AppTheme.dark(),
  // ...
)
```

> ⚠️ **Importante:** elimina cualquier `ThemeMode.system` o variantes light. Esta app es dark-only.

---

## 1 · Reemplazar constantes hardcodeadas

**Buscar en todo `lib/src/presentation/`:**
- `_kAccent`, `_kPrimary`, `_kBg`, `_kCardColor`, `_kBackground`, `Colors.white`, `Colors.black`, hex literales como `Color(0xFF...)`.

**Reemplazar por:**
```dart
final cs = Theme.of(context).colorScheme;
final tokens = Theme.of(context).extension<AppTokens>()!;

// Antes:  color: _kAccent
// Ahora:  color: cs.primary

// Antes:  color: _kBg
// Ahora:  color: cs.background        // = AppColors.bg

// Antes:  color: Colors.white
// Ahora:  color: cs.onBackground      // = AppColors.textPrimary

// Antes:  color: Colors.grey
// Ahora:  color: tokens.textMuted
```

**Mapping completo:**

| Antes (hardcoded) | Ahora |
|---|---|
| `_kAccent` / dorado | `cs.primary` |
| `_kBg` / fondo principal | `cs.background` |
| `_kSurface` / cards | `cs.surface` |
| Texto principal | `cs.onBackground` |
| Texto secundario | `tokens.textMuted` |
| Texto muy sutil | `tokens.textSubtle` |
| Borde divisor | `cs.outline` |
| Border secundario | `tokens.borderSubtle` |
| Verde "success" | `tokens.success` |
| Rojo "danger" | `cs.error` |

---

## 2 · Pantalla por pantalla

> **Para cada pantalla:** abre el prototipo, click el chip correspondiente arriba (Home / Detalle / Carrito / Checkout / Sheet) para ver el destino. Los tokens y dimensiones están en `design_tokens.json`.

### 2.1 · Home — `ClientCategoryListPage` + `ClientProductListItem`

**Cambios principales:**
1. **Hero/Header**: añadir saludo `displayLarge` ("Curaduría / del día") sobre `cs.background`.
2. **Categories scroll horizontal**: `Chip` con `cs.surface` + border `cs.outline`, padding 12×6, radius `AppRadius.pill`. La activa: `cs.primary` con `cs.onPrimary` text.
3. **Grid de productos**: cambiar `ListView` → `GridView.count(crossAxisCount: 2, crossAxisSpacing: 12, mainAxisSpacing: 24)`.
4. **`ClientProductListItem`** se convierte en `ProductCard`:
   ```dart
   class ProductCard extends StatelessWidget {
     // imagen aspect 1/1.15, radius AppRadius.md
     // wishlist icon top-right: 32×32, bg rgba(14,14,16,0.6), heart outline
     // discount badge top-left: bg cs.primary, color cs.onPrimary, 10pt, 600
     // título: bodyMedium, max 2 líneas, ellipsis
     // tenant: labelSmall (uppercase, letterSpacing 0.6), tokens.textMuted
     // precio: 18pt, weight 500, cs.onBackground
     // precio tachado: 13pt, tokens.textSubtle, lineThrough
   }
   ```

### 2.2 · Detalle — `ClientProductDetailContent`

1. **Hero image**: full-bleed (sin padding lateral), aspect 1/1.1, `cs.surfaceAlt` como bg mientras carga.
2. **Carousel de thumbs**: 4 thumbnails 56×56 con border `cs.outline` (la seleccionada usa `cs.primary`).
3. **Título + precio**: `headlineLarge` para nombre, precio en columna derecha 20pt weight 500.
4. **Selectores de variantes**: cada variante es una fila — label en `labelSmall` arriba, opciones como `Chip` seleccionables.
5. **Sticky bottom bar**: `Container(decoration: BoxDecoration(color: cs.background, border: Border(top: BorderSide(color: cs.outline))))` con dos botones lado a lado:
   - "Agregar a wishlist" (`OutlinedButton`)
   - "Agregar al carrito" (`ElevatedButton`)
6. **Importante:** la bottom bar va en `bottomNavigationBar` del `Scaffold` para que el scroll respete el área.

### 2.3 · Bottom Sheet de variantes

```dart
showModalBottomSheet(
  context: context,
  isScrollControlled: true,
  backgroundColor: Theme.of(context).colorScheme.surface,
  shape: const RoundedRectangleBorder(
    borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
  ),
  builder: (_) => VariantSheet(product: product),
);
```

`VariantSheet` (StatefulWidget):
- Drag handle automático del theme (`showDragHandle: true`).
- Header: thumbnail 64×64 + nombre + precio dinámico según variantes.
- Selectores en columna (label `labelSmall` + chips).
- Sticky CTA al fondo: "Agregar al carrito · $XX.XX" (`ElevatedButton`, full width, height 52).

### 2.4 · Carrito — `ClientShoppingBagItem`

1. **Estructura**: `ListView.separated` con divider `cs.outline`.
2. **Cada item**: thumbnail 80×92 (aspect 1/1.15), columna info, columna precio + quantity stepper.
3. **Quantity stepper**: tres elementos en fila — `−` / número / `+`, height 32, border `cs.outline`, radius `AppRadius.sm`.
4. **Bottom bar sticky**: igual que detalle pero con "Subtotal $XX · Checkout".
5. **Cupón**: `TextField` con `suffixIcon` botón "Aplicar" en `cs.primary`.

### 2.5 · Checkout — `ClientPaymentFormContent`

**Una sola pantalla, scroll vertical**, secciones:
1. **Dirección de envío** (header `headlineMedium`) — selector de dirección guardada o `+ Nueva dirección`.
2. **Método de pago** — radio cards.
3. **Resumen del pedido** — collapsible que muestra ítems + subtotal + envío + total.
4. **Sticky bottom bar**: "Pagar $XX.XX" — `ElevatedButton` height 56.

---

## 3 · Componentes reutilizables a crear

Crear en `lib/src/presentation/widgets/`:

```
widgets/
  buttons/
    primary_button.dart       // wraps ElevatedButton with default sizing
    secondary_button.dart     // wraps OutlinedButton
    icon_button_circular.dart // for wishlist/back/share
  cards/
    product_card.dart         // grid item
    cart_item_card.dart       // list item with stepper
  inputs/
    quantity_stepper.dart     // − [n] + with debounce
    chip_selector.dart        // for variant selection
  layout/
    sticky_bottom_bar.dart    // takes a child, applies top border + bg
    section_header.dart       // headline + optional action
  feedback/
    discount_badge.dart       // small bg=primary pill with %
    rating_pill.dart          // ★ 4.8
```

Todos deben leer del theme — **cero hex literales en widgets**.

---

## 4 · Iconografía

Usar **Phosphor Icons** (`phosphor_flutter`) o **Lucide** (`lucide_icons`) — ambos tienen el peso fino que pide el tema oscuro premium.

```yaml
dependencies:
  phosphor_flutter: ^2.1.0
```

Reglas:
- `PhosphorIconsRegular` para navegación e iconos pasivos.
- `PhosphorIconsFill` solo para estados activos (wishlist marcado, tab seleccionado).
- Tamaño base 22px, tap target 44×44.

---

## 5 · Animaciones

- **Botones**: `AnimatedScale` 0.97 al press, duration 100ms.
- **Bottom sheet**: usa el default de Flutter, no override.
- **Wishlist toggle**: `AnimatedSwitcher` 200ms entre outline ↔ fill.
- **Add to cart success**: `SnackBar` con `cs.surface` bg, border-left 2px en `cs.primary`, slide desde abajo.

---

## 6 · Orden recomendado de PRs

1. **PR #1 — Foundation:** copiar `AppTheme.dart`, wireup en `MaterialApp`, deprecar constantes globales.
2. **PR #2 — Widgets shared:** crear `widgets/` con los 10 componentes listados.
3. **PR #3 — Home:** refactor de `ClientCategoryListPage` a grid + `ProductCard`.
4. **PR #4 — Detalle + Sheet:** `ClientProductDetailContent` + `VariantSheet`.
5. **PR #5 — Carrito:** `ClientShoppingBagItem` con nuevo stepper.
6. **PR #6 — Checkout:** `ClientPaymentFormContent` single-screen.
7. **PR #7 — Polish:** animaciones, haptics, empty states, loading skeletons.

---

## 7 · Prompts copy-paste para Claude Code

### Prompt inicial (PR #1)
> Voy a rediseñar mi app Flutter de e-commerce a un tema "Oscuro Premium". En `/handoff/` tengo `AppTheme.dart` y `design_tokens.json`. Por favor:
> 1. Crea `lib/src/presentation/theme/app_theme.dart` con el contenido de `handoff/AppTheme.dart`.
> 2. Agrega `google_fonts` y `phosphor_flutter` al `pubspec.yaml` si no están.
> 3. Aplica el theme en `MaterialApp` (busca en `main.dart` o `app.dart`).
> 4. Busca todas las constantes locales `_kAccent`, `_kPrimary`, `_kBg` y similares en `lib/src/presentation/` y reemplázalas por `Theme.of(context).colorScheme.*` o `Theme.of(context).extension<AppTokens>()!.*` según el mapping del `README.md`.
> 5. No modifiques layouts todavía — solo tokens.

### Prompt por pantalla
> Refactoriza `lib/src/presentation/[ruta]/[archivo].dart` siguiendo la sección "[2.X · Nombre]" del `handoff/README.md`. Mantén la lógica de negocio (BLoCs/providers) intacta — solo cambia layout, widgets y estilos. Crea los widgets compartidos que falten en `lib/src/presentation/widgets/` (ver sección 3). Reporta qué archivos creaste/modificaste al final.

---

## 8 · Checklist final de QA

- [ ] Cero `Color(0xFF...)` en `lib/src/presentation/` (excepto `app_theme.dart`).
- [ ] Cero strings de fuente hardcodeados — todo via `Theme.of(context).textTheme`.
- [ ] Todos los botones tienen height ≥ 48 (accesibilidad).
- [ ] Bottom sheet abre con drag handle visible.
- [ ] El theme se aplica a `Scaffold`, `AppBar`, `BottomNavigationBar`, `Card`, `TextField`, `Chip` sin overrides locales.
- [ ] Probar en iOS + Android — los safe areas deben respetarse en sticky bottom bars.
- [ ] Logo de cada tenant: pequeño y discreto (texto `labelSmall` uppercase + letter-spacing), nunca compite con la marca dorada del marketplace.

---

**Referencia visual:** `Marketplace Redesign.html` con Tweaks → "Oscuro Premium" → ver canvas con prototipo + las 5 pantallas + comparación.
