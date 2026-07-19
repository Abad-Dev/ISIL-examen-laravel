# Decisiones de diseño — Inaut

Este documento recoge las decisiones de producto, interfaz, identidad visual y arquitectura tomadas en el proyecto **Inaut**, una aplicación de gestión de presupuestos personales construida con Laravel.

---

## 1. Producto e identidad

### Nombre: Inaut

La aplicación se llama **Inaut** (`APP_NAME=Inaut` en `.env`). El nombre aparece en:

- Títulos de página y `<title>`
- Logo / marca en pantallas de autenticación
- Navbar del layout principal
- Correos salientes (`MAIL_FROM_NAME` hereda de `APP_NAME`)

### Propósito

Inaut ayuda a organizar **ingresos, gastos y presupuestos** en un solo lugar. Los textos de la interfaz reflejan ese enfoque (por ejemplo, el subtítulo de la portada y las pantallas de registro).

---

## 2. Idioma y tono de voz

### Español neutro

- **Locale:** `APP_LOCALE=es`, `APP_FALLBACK_LOCALE=es`
- **Archivos de traducción:**
  - `lang/es.json` — textos de interfaz (claves en inglés, valores en español)
  - `lang/es/auth.php` — autenticación
  - `lang/es/passwords.php` — restablecimiento de contraseña
  - `lang/es/validation.php` — errores de validación y nombres de atributos

### Tratamiento de **tú** (no usted)

Se eligió un tono directo y cercano, válido en la mayoría de países hispanohablantes:

| Preferido (tú) | Evitado (usted) |
|----------------|-----------------|
| «¿Olvidaste tu contraseña?» | «¿Olvidó su contraseña?» |
| «Ingresa tu correo» | «Ingrese su correo» |
| «Crea tu cuenta» | «Cree su cuenta» |
| «Confirma tu contraseña» | «Confirme su contraseña» |

Las vistas usan `__('clave')` para que todo texto visible pase por los archivos de idioma. Al añadir pantallas nuevas, conviene **no hardcodear** cadenas en Blade.

---

## 3. Stack frontend

### Tailwind CSS v4

- Paquetes: `tailwindcss`, `@tailwindcss/vite`
- Entrada: `resources/css/app.css`
- Plugin en `vite.config.js`

Tailwind es la base del **diseño nuevo** (auth y futuras pantallas). Las clases de utilidad y la paleta personalizada viven en `@theme`.

### Bootstrap 5 (convivencia)

- Entrada: `resources/sass/app.scss`
- Heredado de **Laravel UI** (login/registro originales, navbar, `/home`, portada Bootstrap)

**Decisión:** mantener Bootstrap en paralelo mientras `/home` y partes de la portada aún lo usan. Las pantallas de autenticación **no cargan Bootstrap**; usan solo Tailwind vía `layouts/auth.blade.php`.

### Heroicons

- Paquete: `blade-ui-kit/blade-heroicons`
- Uso: componentes Blade `<x-heroicon-o-*>` y `<x-heroicon-s-*>` en formularios y botones de auth

Se eligieron Heroicons por integración nativa con Blade y coherencia con Tailwind, sin depender de una fuente de iconos aparte.

### Flujo de assets (Vite)

**Decisión explícita:** no exigir `npm run dev` en paralelo con `php artisan serve` en el día a día.

```bash
npm run build    # cuando cambien CSS, JS o clases Tailwind
php artisan serve
```

`npm run build` compila a `public/build/`. Laravel sirve esos assets estáticos sin necesidad del servidor de Vite en desarrollo.

---

## 4. Tipografía

### Instrument Sans

- Fuente principal de la aplicación
- Carga: [Bunny Fonts](https://fonts.bunny.net) (`instrument-sans:400,500,600,700`)
- Definida en Tailwind como `--font-sans` en `resources/css/app.css`
- Replicada en Bootstrap vía `$font-family-sans-serif` en `resources/sass/_variables.scss`

**Motivo:** aspecto moderno y legible, alineado con interfaces de producto financiero sin ser corporativo rígido.

---

## 5. Paleta de colores

Cuatro colores base, pensados para semántica financiera:

| Token Tailwind | Hex | Uso previsto |
|----------------|-----|--------------|
| `palette-green` | `#8DDA90` | Ingresos, acciones primarias, éxito |
| `palette-yellow` | `#FAFA8B` | Acentos, destacados suaves |
| `palette-orange` | `#F6C87B` | Advertencias, enlaces secundarios |
| `palette-red` | `#DB5656` | Gastos, errores, acciones destructivas |

### Dónde se define

- **Tailwind:** `resources/css/app.css` → `@theme { --color-palette-* }`
- **Bootstrap:** `resources/sass/_variables.scss` → `$primary`, `$success`, `$warning`, `$danger`, etc.

### Uso en interfaz

```html
<!-- Tailwind -->
<button class="bg-palette-green text-slate-800">Acción principal</button>
<p class="text-palette-red">Error de validación</p>

<!-- Bootstrap -->
<button class="btn btn-primary">Verde (#8DDA90)</button>
<button class="btn btn-danger">Rojo (#DB5656)</button>
```

### Marca visual en tarjetas de auth

Cada tarjeta de autenticación incluye una **barra superior de 4 franjas** (verde → amarillo → naranja → rojo) como firma visual de la paleta.

---

## 6. Pantallas de autenticación

### Layout dedicado

- Archivo: `resources/views/layouts/auth.blade.php`
- Solo carga `resources/css/app.css` (Tailwind + Instrument Sans)
- **No** incluye Bootstrap ni el navbar de `layouts/app.blade.php`

### Estética

- Fondo claro (`#fafaf5`) con manchas difuminadas en los cuatro colores de la paleta
- Tarjetas blancas, bordes redondeados (`rounded-2xl`), sombra suave
- Logo: icono `banknotes` (Heroicon) sobre fondo `palette-green`
- Botón principal: clase `.auth-btn-primary` (verde)
- Errores: borde y texto `palette-red`
- Enlaces: `.auth-link` con subrayado naranja en footer

### Componentes reutilizables

| Componente | Archivo | Rol |
|------------|---------|-----|
| `x-auth.card` | `components/auth/card.blade.php` | Contenedor con barra de colores, título y subtítulo |
| `x-auth.input` | `components/auth/input.blade.php` | Label + input + icono + error |
| `x-auth.alert` | `components/auth/alert.blade.php` | Mensajes de éxito/error |

### Clases utilitarias (auth)

Definidas en `@layer components` dentro de `app.css`:

- `.auth-input` / `.auth-input-error`
- `.auth-btn-primary`
- `.auth-link`

### Pantallas cubiertas

- Iniciar sesión (`/login`)
- Registro (`/register`)
- Solicitar restablecimiento de contraseña
- Restablecer contraseña
- Confirmar contraseña
- Verificar correo electrónico

---

## 7. Autenticación y dominio (backend)

Decisiones de arquitectura que afectan al producto:

### Un solo modelo de usuario: `Usuario`

Antes existían dos sistemas (`User` en `users` y `Usuario` en `usuarios`). Se unificó en **`Usuario`** como modelo autenticable:

- Login/registro web crean registros en `usuarios`
- Categorías, transacciones y presupuestos pertenecen a ese mismo usuario
- `config/auth.php` apunta a `App\Models\Usuario::class`

### API protegida

Todas las rutas en `routes/api.php` requieren middleware `auth`. Cada controlador filtra datos por el usuario autenticado (`auth()->id()`).

No hay registro público vía API; el alta es solo por la web (`/register`).

### Sesión en rutas API

En `bootstrap/app.php` se añadió soporte de cookies/sesión en el grupo `api` para que la misma sesión web funcione con peticiones a la API desde el navegador.

---

## 8. Mapa de archivos clave

```
config/
├── money.php                 # Moneda PEN, símbolo S/, locale es_PE

app/
└── Support/Money.php         # Formateo de montos en soles

resources/
├── css/app.css              # Tailwind v4, paleta, clases auth
├── sass/
│   ├── app.scss             # Bootstrap
│   └── _variables.scss      # Paleta + Instrument Sans (Bootstrap)
└── views/
    ├── layouts/
    │   ├── auth.blade.php    # Layout auth (solo Tailwind)
    │   └── app.blade.php     # Layout app (Bootstrap + Tailwind)
    ├── components/
    │   ├── auth/             # card, input, alert
    │   ├── money.blade.php   # Montos en soles
    │   ├── confirm-modal.blade.php
    │   └── cuentas/form-modal.blade.php
    └── auth/                 # Vistas de autenticación

resources/js/
├── confirm-modal.js          # Modal de confirmación reutilizable
├── cuenta-modal.js           # Modal crear/editar cuenta

lang/
├── es.json                   # Traducciones UI
└── es/                       # auth, passwords, validation

.env                          # APP_NAME, APP_LOCALE=es
vite.config.js                # Tailwind + entradas CSS/JS
```

---

## 9. Guía para extender el diseño

Al crear pantallas nuevas (por ejemplo `/home` o CRUD de categorías):

1. **Preferir Tailwind** y la paleta `palette-*` para alinearse con auth.
2. **Usar Instrument Sans** (ya global en `body`).
3. **Traducir** con `__('Clave en inglés')` y añadir la entrada en `lang/es.json`.
4. **Tono tú:** revisar que no aparezcan «usted», «su cuenta» en sentido formal, «ingrese», etc.
5. **Iconos:** Heroicons outline (`heroicon-o-*`) en campos y botones; solid (`heroicon-s-*`) en logo o énfasis. Ver sección 14.
6. **Semántica de color:** verde = positivo/ingreso; rojo = negativo/error; naranja = enlaces/avisos; amarillo = acentos.
7. **Compilar:** ejecutar `npm run build` tras cambios de estilos.
8. **Montos:** usar `<x-money />` o `App\Support\Money::format()`; la app es solo soles (PEN). Ver sección 12.
9. **Confirmaciones:** usar `<x-confirm-modal />` y `data-confirm-submit`; no usar `window.confirm()` ni `window.alert()`. Ver sección 13.

### Plantilla de layout sugerida para nuevas secciones autenticadas

Reutilizar patrones de `layouts/auth.blade.php` (fondo claro, tarjeta con barra de colores) o migrar `layouts/app.blade.php` a Tailwind puro cuando se retire Bootstrap.

---

## 10. Decisiones pendientes / deuda técnica

| Área | Estado actual | Recomendación futura |
|------|---------------|----------------------|
| `/home` y navbar | Bootstrap | Migrar a Tailwind + paleta Inaut |
| Portada (`welcome`) | Bootstrap + textos traducidos | Unificar estilo con auth |
| Tabla `users` | Existe pero no se usa | Eliminar en migración futura si no hace falta |
| `User` model | Alias deprecado de `Usuario` | Remover cuando no queden referencias |

---

## 11. Referencia rápida de comandos

```bash
# Desarrollo
php artisan serve
npm run build          # tras cambios de frontend

# Datos de prueba
php artisan migrate:fresh --seed
# Credenciales: test@example.com / password
```

---

## 12. Moneda: soles peruanos (PEN)

Inaut opera **exclusivamente en soles peruanos**. No hay soporte multi-moneda en la interfaz ni en la lógica de negocio.

### Decisión de producto

| Aspecto | Valor |
|---------|--------|
| Código ISO | `PEN` |
| Símbolo | `S/` |
| Locale de formato | `es_PE` |
| Nombre | sol peruano / soles |

Todos los montos visibles al usuario (saldos de cuentas, transacciones, presupuestos, etc.) deben mostrarse en soles. Los valores numéricos en base de datos representan cantidades en PEN sin conversión.

### Dónde se define

| Archivo | Rol |
|---------|-----|
| `config/money.php` | Moneda, símbolo, locale y nombres |
| `app/Support/Money.php` | Formateo centralizado (`Money::format()`) |
| `resources/views/components/money.blade.php` | Componente Blade `<x-money :amount="..." />` |
| `database/migrations/..._create_usuarios_table.php` | Columna `moneda` con default `PEN` |

### Reglas de implementación

1. **Mostrar montos** con `<x-money :amount="$valor" />` o `Money::format($valor)`; no usar `number_format()` suelto en vistas.
2. **Alta de usuario:** `moneda` se asigna siempre a `PEN` (registro web y evento `creating` del modelo `Usuario`).
3. **API:** no se permite cambiar `moneda` vía `PUT/PATCH /api/usuario`. Los endpoints de transacciones, presupuestos y cuentas asumen PEN.
4. **Formularios:** etiquetar campos monetarios como montos en soles (ej. «Saldo en soles»).
5. **Futuro:** si se añaden transacciones o presupuestos en la web, reutilizar el mismo formateo; no introducir selectores de moneda.

### Ejemplo de formato

```php
use App\Support\Money;

Money::format(1234.5); // "S/ 1,234.50" (según locale es_PE)
```

```blade
<x-money :amount="$cuenta->saldo" />
```

---

## 13. Modales de confirmación

Inaut **no usa diálogos nativos del navegador** (`window.confirm()`, `window.alert()`, `window.prompt()`). Las acciones destructivas o irreversibles se confirman con un modal propio de la interfaz.

### Por qué

- Coherencia visual con Tailwind, paleta Inaut y dark mode.
- Textos traducibles vía `lang/es.json`.
- Mejor experiencia en mobile que los cuadros del sistema operativo.

### Componentes

| Pieza | Archivo | Rol |
|-------|---------|-----|
| Modal | `resources/views/components/confirm-modal.blade.php` | UI de confirmación (título, mensaje, cancelar, confirmar) |
| Script | `resources/js/confirm-modal.js` | Apertura/cierre y enlace con formularios |
| Layout | `layouts/app.blade.php` | Incluye `<x-confirm-modal />` en vistas autenticadas |

El script se carga desde `resources/js/app.js` en el layout autenticado.

### Uso en formularios

Añadir atributos `data-confirm-*` al `<form>` que ejecuta la acción:

```blade
<form
    method="POST"
    action="{{ route('cuentas.destroy', $cuenta) }}"
    data-confirm-submit
    data-confirm-title="{{ __('Delete account') }}"
    data-confirm-message="{{ __('Are you sure you want to delete this account?') }}"
    data-confirm-label="{{ __('Delete') }}"
    data-confirm-variant="danger"
>
    @csrf
    @method('DELETE')
    <button type="submit">{{ __('Delete') }}</button>
</form>
```

| Atributo | Obligatorio | Descripción |
|----------|-------------|-------------|
| `data-confirm-submit` | Sí | Activa la interceptación del envío |
| `data-confirm-title` | Sí | Título del modal |
| `data-confirm-message` | Sí | Cuerpo del mensaje |
| `data-confirm-label` | No | Texto del botón confirmar (default: «Confirmar») |
| `data-confirm-variant` | No | `danger` (rojo, default) o `primary` (verde) |

Al pulsar enviar, se abre el modal. Solo si el usuario confirma se envía el formulario.

### Mensajes informativos vs confirmación

- **`x-auth.alert`:** mensajes de éxito o error tras una acción (flash de sesión). No es un `alert()` del navegador.
- **`<x-confirm-modal />`:** preguntar antes de ejecutar una acción.

### Reglas

1. No introducir `window.confirm()` ni `window.alert()` en JS del proyecto.
2. Traducir título, mensaje y etiquetas del botón con `__()`.
3. Acciones destructivas (eliminar) usan `data-confirm-variant="danger"`.
4. Cerrar con **Cancelar**, clic en el fondo o tecla **Escape** equivale a rechazar.

---

## 14. Botones primarios con Heroicons

Los botones de acción principal (clase `.auth-btn-primary`) llevan **icono + texto** en la misma fila, igual en auth, modales y pantallas autenticadas.

### Patrón

```blade
<button type="submit" class="auth-btn-primary gap-2 sm:w-auto sm:px-6">
    <x-heroicon-o-plus class="size-5 shrink-0" />
    {{ __('Create account') }}
</button>
```

| Regla | Detalle |
|-------|---------|
| Clase base | `.auth-btn-primary` (definida en `app.css`) |
| Icono | `<x-heroicon-o-*>` outline, coherente con el resto de la UI |
| Tamaño | `size-5` (20px), nunca más grande en botones |
| Layout | `shrink-0` en el icono para que no se estire en flex |
| Espaciado | `gap-2` entre icono y texto |

### Referencia por acción

| Acción | Icono | Ejemplo en el proyecto |
|--------|-------|-------------------------|
| Crear / alta | `heroicon-o-plus` | «Nueva cuenta», «Crear cuenta» |
| Guardar cambios | `heroicon-o-check` | Modal editar cuenta |
| Registro | `heroicon-o-user-plus` | `/register` |
| Iniciar sesión | `heroicon-o-arrow-right-on-rectangle` | `/login` |

### Modales con crear y editar

En formularios que comparten un modal (p. ej. cuentas), **no** intercambiar icono y etiqueta dentro de un solo botón con JavaScript. Eso provoca iconos mal dimensionados o superpuestos.

**Decisión:** dos botones `type="submit"` en el mismo formulario, uno visible por modo:

- `[data-cuenta-submit-create]` — visible al crear (`heroicon-o-plus` + «Crear cuenta»)
- `[data-cuenta-submit-edit]` — visible al editar (`heroicon-o-check` + «Guardar cambios»)

El script `cuenta-modal.js` alterna la clase `hidden` entre ambos al abrir el modal en modo crear o editar.

### Qué evitar

- Un solo botón con iconos distintos que se muestran/ocultan por separado del texto.
- Iconos sin `size-5` ni `shrink-0` (p. ej. `size-4` suelto o SVG a tamaño por defecto).
- Mezclar estilos de botón distintos a `.auth-btn-primary` para acciones principales en modales.

### Dónde aplica

- Pantallas auth (`login`, `register`, restablecer contraseña).
- Botón «Nueva cuenta» en `/cuentas`.
- Pie del modal `components/cuentas/form-modal.blade.php`.

Al añadir CRUD en modales (categorías, transacciones), reutilizar este patrón.

---

*Última actualización: documento alineado con identidad Inaut, paleta, auth unificada, internacionalización en español, moneda única PEN, modales de confirmación y botones primarios con Heroicons.*
