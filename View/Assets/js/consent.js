/* ─────────────────────────────────────────────────────────────
   Sistema de aviso de cookies + validación del login
   Sigue el patrón de la teoría jQuery:
     $(document).ready(iniciar)
     $('selector').click(function(){ ... })
     $('selector').addClass / .removeClass / .show / .hide / .fadeIn / .fadeOut

   Persistencia:
     - localStorage.cookieConsent  (mecanismo principal, según el issue)
     - document.cookie 'cookieConsent'  (fallback para navegadores
       que bloquean localStorage en privado / Shields / ITP)
   ───────────────────────────────────────────────────────────── */

$(document).ready(iniciarCookies);

function iniciarCookies() {
  var STORAGE_KEY = "cookieConsent";

  // ── 1. Persistencia ─────────────────────────────────────────
  function leerEstado() {
    try {
      var v = localStorage.getItem(STORAGE_KEY);
      if (v === "accepted" || v === "rejected") return v;
    } catch (e) { /* localStorage bloqueado: usaremos cookie */ }

    var match = document.cookie.match(/(?:^|;\s*)cookieConsent=([^;]+)/);
    if (match) {
      var c = decodeURIComponent(match[1]);
      if (c === "accepted" || c === "rejected") return c;
    }
    return null;
  }

  function guardarEstado(valor) {
    try { localStorage.setItem(STORAGE_KEY, valor); } catch (e) {}
    document.cookie = "cookieConsent=" + encodeURIComponent(valor)
      + "; path=/; max-age=" + (60 * 60 * 24 * 365) + "; SameSite=Lax";
  }

  // ── 2. Aplicar estado a la UI con jQuery ────────────────────
  // Usamos la clase utilitaria '.is-hidden' (display:none !important)
  // que jQuery alterna con addClass / removeClass.

  function aplicarEstado(estado) {
    if (estado === "accepted") {
      // Cookies aceptadas: ocultar aviso, habilitar login
      $("#cookie-banner").addClass("is-hidden");
      $("#cookie-reopen").addClass("is-hidden");
      $("#cookie-blocked-msg").addClass("is-hidden");
      $(".js-cookie-protected").prop("disabled", false);
    } else if (estado === "rejected") {
      // Cookies rechazadas: ocultar login, mostrar reopen + aviso de bloqueo
      $("#cookie-banner").addClass("is-hidden");
      $("#cookie-reopen").removeClass("is-hidden");
      $("#cookie-blocked-msg").removeClass("is-hidden");
      $(".js-cookie-protected").prop("disabled", true);
    } else {
      // Sin decisión: mostrar el aviso, login deshabilitado pero visible
      $("#cookie-banner").removeClass("is-hidden");
      $("#cookie-reopen").addClass("is-hidden");
      $("#cookie-blocked-msg").addClass("is-hidden");
      $(".js-cookie-protected").prop("disabled", true);
    }
  }

  // ── 3. Eventos jQuery ───────────────────────────────────────
  // Click en "Aceptar cookies"
  $("#cookie-accept").click(function () {
    guardarEstado("accepted");
    $("#cookie-banner").fadeOut(250, function () {
      $(this).addClass("is-hidden").css("display", "");
    });
    $(".js-cookie-protected").prop("disabled", false);
    $("#cookie-blocked-msg").addClass("is-hidden");
    $("#cookie-reopen").addClass("is-hidden");
  });

  // Click en "Rechazar"
  $("#cookie-reject").click(function () {
    guardarEstado("rejected");
    $("#cookie-banner").fadeOut(250, function () {
      $(this).addClass("is-hidden").css("display", "");
    });
    $(".js-cookie-protected").prop("disabled", true);
    $("#cookie-blocked-msg").removeClass("is-hidden");
    $("#cookie-reopen").removeClass("is-hidden");
  });

  // Click en el botón flotante para reabrir el aviso
  $("#cookie-reopen").click(function () {
    $(this).addClass("is-hidden");
    $("#cookie-blocked-msg").addClass("is-hidden");
    $("#cookie-banner").removeClass("is-hidden").hide().fadeIn(250);
  });

  // ── 4. Validación del formulario de login ───────────────────
  // Bloquea el envío (incluso si pulsan Enter en un input)
  // mientras el estado no sea 'accepted'.
  $(".js-cookie-form").submit(function (e) {
    var estado = leerEstado();
    if (estado !== "accepted") {
      e.preventDefault();
      if (estado === "rejected") {
        $("#cookie-blocked-msg").removeClass("is-hidden");
        $("#cookie-reopen").removeClass("is-hidden");
      } else {
        $("#cookie-banner").removeClass("is-hidden").hide().fadeIn(250);
      }
    }
  });

  // ── 5. Inicialización al cargar la página ───────────────────
  aplicarEstado(leerEstado());
}
