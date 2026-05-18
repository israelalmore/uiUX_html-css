# Informe de funcionalidades — jQuery y Slick

---

## Portada

| Campo            | Valor                                   |
| ---------------- | --------------------------------------- |
| **Proyecto**     | Forever Events — Plataforma de eventos  |
| **Asignatura**   | _[a rellenar]_                          |
| **Fecha**        | 2026-05-18                              |
| **Curso**        | _[a rellenar]_                          |
| **Integrantes**  | _[a rellenar — nombre 1, nombre 2, …]_  |
| **Repositorio**  | _[a rellenar — URL Git]_                |

---

## Índice

1. [Introducción](#1-introducción)
2. [Funcionalidades jQuery](#2-funcionalidades-jquery)
   1. [Modal reutilizable con overlay](#21-modal-reutilizable-con-overlay)
   2. [Tooltip on-hover sobre imágenes](#22-tooltip-on-hover-sobre-imágenes)
   3. [Aviso de cookies + control de login](#23-aviso-de-cookies--control-de-login)
3. [Funcionalidades Slick](#3-funcionalidades-slick)
   1. [Slider de conciertos](#31-slider-de-conciertos)
   2. [Slider de promotores](#32-slider-de-promotores)
4. [Arquitectura de ficheros](#4-arquitectura-de-ficheros)
5. [Cómo probarlo](#5-cómo-probarlo)

---

## 1. Introducción

Este informe documenta las funcionalidades de **interactividad cliente** añadidas al proyecto _Forever Events_ durante la actividad. Se han implementado tres componentes basados en **jQuery** y dos **sliders responsive** con **Slick Carousel**, manteniendo la coherencia visual (tema oscuro/azul, acento dorado) del resto del proyecto.

Todo el JavaScript se ha estructurado en módulos independientes dentro de `View/Assets/js/`, con sus correspondientes hojas de estilos en `View/Assets/css/components/`, importadas desde `main.css`.

---

## 2. Funcionalidades jQuery

### 2.1. Modal reutilizable con overlay

**Issue resuelto:** modal centrado con overlay semitransparente, cierre por click externo, botón "×" y tecla Escape.

**Archivos:**
- `View/Assets/css/components/modal.css`
- `View/Assets/js/modal.js`
- Integrado en `View/pages/eventDetails.php` (reemplaza `confirm()`/`alert()` nativos).

**API expuesta sobre `$.uiModal`:**

```js
$.uiModal.confirm({
  title: 'Eliminar evento',
  message: '¿Seguro que quieres eliminarlo?',
  confirmText: 'Eliminar',
  variant: 'danger',
  onConfirm: function () {
    // lógica de eliminación
  }
});

$.uiModal.alert({ title: 'Aviso', message: 'Acción completada.' });
$.uiModal.open({ title, body, actions, onClose });
$.uiModal.close();
```

**Conceptos jQuery aplicados (según la teoría facilitada):**

| Concepto teoría        | Uso en el código                                                           |
| ---------------------- | -------------------------------------------------------------------------- |
| `$(selector)`          | `$("#ui-modal-root")`, `$("body")`                                         |
| `.on('click', ...)`    | Cierre por click en overlay (`[data-modal-close]`)                         |
| `.addClass / .removeClass` | `$root.addClass("is-open")` para mostrar / `.removeClass(...)` para ocultar |
| `.attr()`              | `aria-hidden="true|false"` y `tabindex`                                    |
| `.append()`            | Inserta el contenedor `#ui-modal-root` en `<body>` la primera vez          |
| `.html() / .text()`    | Inserción de cuerpo (HTML) y título (texto, seguro frente a XSS)           |
| Eventos teclado        | `$(document).on("keydown.uiModal", ...)` para cerrar con `Escape`          |

---

### 2.2. Tooltip on-hover sobre imágenes

**Issue resuelto:** mostrar información adicional al pasar el ratón por una imagen, ocultarla al salir, con animación fluida (`fadeIn` / `fadeOut`).

**Archivos:**
- `View/Assets/css/components/imageHover.css`
- `View/Assets/js/imageHover.js`
- Integrado en `View/pages/events.php` (cada tarjeta de evento).

**Uso:** basta con añadir atributos `data-hover-msg` (y opcionalmente `data-hover-title`) a cualquier `<img>`:

```html
<img
  src="evento.jpg"
  alt="…"
  data-hover-title="Indie Fest"
  data-hover-msg="12/04/2026 · 20:00h · Barcelona" />
```

**Núcleo jQuery (siguiendo la doc — `.hover(enter, leave)` + efectos):**

```js
$img.hover(
  function () {                                  // mouseenter
    $overlay.stop(true, true).fadeIn(200);
  },
  function () {                                  // mouseleave
    $overlay.stop(true, true).fadeOut(200);
  }
);
```

**Detalles:**
- El overlay se posiciona absolutamente sobre la imagen con `inset: 0`, lo que garantiza que funciona con cualquier tamaño de imagen.
- `pointer-events: none` impide que el overlay bloquee clicks o links del padre.
- El contenido se inyecta con `.text()` (no `.html()`) → seguro frente a XSS.

---

### 2.3. Aviso de cookies + control de login

**Issue resuelto:** aviso de cookies al entrar; al aceptar se muestra el botón de login; al rechazar se oculta y se ofrece un botón flotante para reabrir el aviso. Estado persistido en `localStorage`.

**Archivos:**
- `View/Assets/css/components/cookieBanner.css`
- `View/Assets/js/cookieBanner.js`
- Integrado en `View/pages/login.php`.

**Estados (clave `cookieConsent` en `localStorage`):**

| Valor       | Comportamiento                                                              |
| ----------- | --------------------------------------------------------------------------- |
| `null`      | (primera visita) muestra el banner, login oculto y `disabled`               |
| `accepted`  | banner oculto, login visible y habilitado, submit permitido                 |
| `rejected`  | banner oculto, login oculto, botón "Cookies" visible para reabrir el aviso  |

**Núcleo jQuery (siguiendo la doc):**

```js
$(document).ready(iniciarCookies);

function iniciarCookies() {
  // $(selector).click(...) según la teoría
  $("#cookie-accept").click(function () {
    localStorage.setItem("cookieConsent", "accepted");
    aplicarEstado("accepted");
  });

  // Efectos visuales fadeIn / fadeOut
  function aplicarEstado(estado) {
    if (estado === "accepted") {
      $("#cookie-banner").fadeOut(250);
      $(".js-cookie-protected").prop("disabled", false).fadeIn(250);
    }
    // … rejected / sin decisión
  }

  // Bloquea el envío del formulario (incluye Enter en inputs) si aún no se han aceptado
  $(".js-cookie-form").submit(function (e) {
    if (estadoActual !== "accepted") e.preventDefault();
  });
}
```

**Persistencia:** `localStorage.setItem("cookieConsent", "accepted"|"rejected")`, leída con `localStorage.getItem(…)`. Las llamadas van envueltas en `try/catch` por si el navegador deshabilita `localStorage` (Safari modo privado, etc.).

---

## 3. Funcionalidades Slick

### 3.1. Slider de conciertos

**Archivos:**
- `View/Assets/css/components/sliders.css` (tarjeta `.concert-card` + override del tema Slick).
- `View/Assets/js/sliders.js`
- Integrado en `View/pages/landingPage.php`, sección `.concerts-section`.

**Configuración:**

```js
$(".concerts-slider").slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  infinite: true,
  arrows: true,
  dots: true,
  autoplay: true,
  autoplaySpeed: 4000,
  pauseOnHover: true,
  speed: 500,
  responsive: [
    { breakpoint: 1024, settings: { slidesToShow: 2 } },
    { breakpoint: 768,  settings: { slidesToShow: 1, arrows: false } },
    { breakpoint: 480,  settings: { slidesToShow: 1, arrows: false, dots: true } }
  ]
});
```

**Breakpoints (mínimo 3, como pide el issue):** 1024 px, 768 px y 480 px.

### 3.2. Slider de promotores

**Configuración independiente** del anterior (sin autoplay, más slides visibles, navegación manual):

```js
$(".promoters-slider").slick({
  slidesToShow: 4,
  slidesToScroll: 2,
  infinite: true,
  arrows: true,
  dots: false,
  autoplay: false,
  speed: 400,
  responsive: [
    { breakpoint: 1200, settings: { slidesToShow: 3 } },
    { breakpoint: 900,  settings: { slidesToShow: 2 } },
    { breakpoint: 600,  settings: { slidesToShow: 1, arrows: false, dots: true } }
  ]
});
```

**Diferencias con el slider de conciertos:**

| Aspecto         | Conciertos       | Promotores               |
| --------------- | ---------------- | ------------------------ |
| Contenido       | imagen + título  | logo + nombre + descripción |
| Slides visibles | 3 → 2 → 1        | 4 → 3 → 2 → 1            |
| Autoplay        | Sí (4 s)         | No (manual)              |
| Dots            | Sí               | Solo en móvil            |
| Breakpoints     | 1024 / 768 / 480 | 1200 / 900 / 600         |

---

## 4. Arquitectura de ficheros

```
View/
├── Assets/
│   ├── css/
│   │   ├── main.css                 ← @imports
│   │   └── components/
│   │       ├── modal.css            ← (2.1)
│   │       ├── imageHover.css       ← (2.2)
│   │       ├── cookieBanner.css     ← (2.3)
│   │       └── sliders.css          ← (3.1, 3.2)
│   └── js/
│       ├── modal.js                 ← (2.1)  $.uiModal
│       ├── imageHover.js            ← (2.2)
│       ├── cookieBanner.js          ← (2.3)
│       └── sliders.js               ← (3.1, 3.2)
└── pages/
    ├── eventDetails.php             ← usa modal
    ├── events.php                   ← usa imageHover
    ├── login.php                    ← usa cookieBanner
    └── landingPage.php              ← usa sliders Slick
```

**Cargas externas:**
- jQuery 3.7.1 → `https://code.jquery.com/jquery-3.7.1.min.js`
- Slick 1.8.1 → `https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/`

---

## 5. Cómo probarlo

### Modal
1. Inicia sesión y entra en `eventDetails.php?id=…` (siendo organizador del evento).
2. Pulsa **Eliminar evento** → aparece el modal centrado con overlay.
3. Verifica: click fuera cierra, `Esc` cierra, "×" cierra.

### Tooltip imagen
1. Entra a `events.php`.
2. Pasa el ratón sobre cualquier tarjeta → aparece overlay con título + fecha/hora/ciudad.

### Cookies + login
1. `localStorage.clear()` en consola y recarga `login.php`.
2. Se muestra el banner; el botón "Iniciar Sesión" está oculto y deshabilitado.
3. Rellena credenciales y prueba **Enter** → no envía (bloqueo del `submit`).
4. Pulsa **Aceptar** → banner desaparece, botón aparece habilitado.
5. Recarga la página → la decisión persiste (no vuelve a mostrar el aviso).
6. `localStorage.setItem("cookieConsent","rejected")` + recarga → ves el botón flotante **Cookies**; al pulsarlo reaparece el banner.

### Sliders
1. Entra en `landingPage.php`.
2. **Conciertos:** verifica autoplay, flechas, dots y que pasa de 3 → 2 → 1 columnas al estrechar la ventana.
3. **Promotores:** verifica que tiene configuración distinta (más slides, sin autoplay, sin dots en desktop) y propios breakpoints.

---

_Fin del informe._
