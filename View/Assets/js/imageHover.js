/* ─────────────────────────────────────────────────────────────
   Tooltip on-hover sobre imágenes (jQuery)
   - Usa el evento .hover(enterFn, leaveFn) de jQuery
   - Animación fluida con .fadeIn() / .fadeOut()
   - Selector genérico: cualquier <img data-hover-msg="..."> (o
     con data-hover-title="...").
   - Compatible con cualquier tamaño de imagen porque el overlay
     se posiciona con inset:0 sobre el contenedor.
   ───────────────────────────────────────────────────────────── */

(function ($) {
  "use strict";

  if (!$) {
    console.error("imageHover: jQuery no está cargado.");
    return;
  }

  var FADE_DURATION = 200;

  /**
   * Construye el overlay con título (opcional) y mensaje.
   * El texto se inserta con .text() para evitar inyecciones HTML.
   */
  function buildOverlay(title, message) {
    var $overlay = $(
      '<div class="img-hover-msg" aria-hidden="true">' +
        '<span class="img-hover-msg__inner">' +
          (title ? '<span class="img-hover-msg__title"></span>' : '') +
          '<span class="img-hover-msg__body"></span>' +
        '</span>' +
      '</div>'
    );
    if (title) $overlay.find(".img-hover-msg__title").text(title);
    $overlay.find(".img-hover-msg__body").text(message || "");
    return $overlay;
  }

  /**
   * Aplica el comportamiento hover a todas las imágenes con data-hover-msg.
   * Cada imagen se procesa una sola vez (flag .data('imgHoverReady', true)).
   */
  function init() {
    $("img[data-hover-msg]").each(function () {
      var $img = $(this);
      if ($img.data("imgHoverReady")) return;
      $img.data("imgHoverReady", true);

      var $parent = $img.parent();
      $parent.addClass("has-img-hover");

      var title   = $img.attr("data-hover-title") || "";
      var message = $img.attr("data-hover-msg")   || "";

      // Creamos el overlay una vez (oculto) y lo reutilizamos
      var $overlay = buildOverlay(title, message).hide();
      $parent.append($overlay);

      // jQuery .hover(enter, leave) → fadeIn / fadeOut suaves
      $img.hover(
        function () {
          $overlay.stop(true, true).fadeIn(FADE_DURATION);
        },
        function () {
          $overlay.stop(true, true).fadeOut(FADE_DURATION);
        }
      );
    });
  }

  // Auto-inicialización al cargar el DOM
  $(init);

  // Exponemos init() para poder re-aplicarlo si se inyectan imágenes dinámicamente
  $.imageHover = { init: init };
})(window.jQuery);
