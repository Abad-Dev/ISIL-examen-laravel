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
resources/
├── css/app.css              # Tailwind v4, paleta, clases auth
├── sass/
│   ├── app.scss             # Bootstrap
│   └── _variables.scss      # Paleta + Instrument Sans (Bootstrap)
└── views/
    ├── layouts/
    │   ├── auth.blade.php    # Layout auth (solo Tailwind)
    │   └── app.blade.php     # Layout app (Bootstrap + Tailwind)
    ├── components/auth/      # card, input, alert
    └── auth/                 # Vistas de autenticación

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
5. **Iconos:** Heroicons outline (`heroicon-o-*`) en campos; solid (`heroicon-s-*`) en logo o énfasis.
6. **Semántica de color:** verde = positivo/ingreso; rojo = negativo/error; naranja = enlaces/avisos; amarillo = acentos.
7. **Compilar:** ejecutar `npm run build` tras cambios de estilos.

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

*Última actualización: documento alineado con el estado del proyecto tras definir identidad Inaut, paleta, auth unificada e internacionalización en español.*
