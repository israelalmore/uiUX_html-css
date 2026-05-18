/* ─────────────────────────────────────────────────────────────
   Modal reutilizable basado en jQuery
   - Centrado en pantalla con overlay semitransparente
   - Cierre por click en overlay, botón "x" o tecla Escape
   - API:
       $.uiModal.open({ title, body, actions, onClose })
       $.uiModal.close()
       $.uiModal.confirm({ title, message, confirmText, cancelText, variant, onConfirm, onCancel })
       $.uiModal.alert({ title, message, buttonText })
   ───────────────────────────────────────────────────────────── */

(function ($) {
  "use strict";

  if (!$) {
    console.error("uiModal: jQuery no está cargado.");
    return;
  }

  // Selector raíz del modal (uno solo, reutilizado en toda la app)
  var ROOT_SELECTOR = "#ui-modal-root";

  // Devuelve (o crea) el contenedor raíz del modal en el <body>
  function getRoot() {
    var $root = $(ROOT_SELECTOR);
    if ($root.length) return $root;

    $root = $(
      '<div id="ui-modal-root" class="ui-modal" role="dialog" aria-modal="true" aria-hidden="true">' +
        '<div class="ui-modal__overlay" data-modal-close></div>' +
        '<div class="ui-modal__dialog">' +
          '<button type="button" class="ui-modal__close" data-modal-close aria-label="Cerrar">&times;</button>' +
          '<h2 class="ui-modal__title"></h2>' +
          '<div class="ui-modal__body"></div>' +
          '<div class="ui-modal__actions"></div>' +
        '</div>' +
      '</div>'
    );
    $("body").append($root);

    // Click en overlay o en cualquier elemento con [data-modal-close] cierra el modal
    $root.on("click", "[data-modal-close]", function () {
      api.close();
    });

    // Evita que clicks dentro del diálogo se propaguen al overlay
    $root.on("click", ".ui-modal__dialog", function (e) {
      e.stopPropagation();
    });

    return $root;
  }

  // Callback a ejecutar cuando el modal se cierre (configurable por open)
  var onCloseCallback = null;

  var api = {
    /**
     * Abre el modal con un contenido arbitrario.
     * @param {Object}   opts
     * @param {String}   opts.title    Título del modal (opcional)
     * @param {String}   opts.body     Cuerpo en HTML o texto (opcional)
     * @param {Array}    opts.actions  Lista de botones: [{ text, variant, onClick, closeOnClick }]
     * @param {Function} opts.onClose  Callback al cerrar el modal
     */
    open: function (opts) {
      opts = opts || {};
      var $root    = getRoot();
      var $title   = $root.find(".ui-modal__title");
      var $body    = $root.find(".ui-modal__body");
      var $actions = $root.find(".ui-modal__actions");

      // Título: si no hay, lo ocultamos
      if (opts.title) {
        $title.text(opts.title).show();
      } else {
        $title.empty().hide();
      }

      // Cuerpo: aceptamos HTML
      $body.html(opts.body || "");

      // Acciones: limpiamos y reconstruimos
      $actions.empty();
      var actions = Array.isArray(opts.actions) ? opts.actions : [];

      actions.forEach(function (action) {
        var variant = action.variant || "ghost";
        var $btn = $(
          '<button type="button" class="ui-modal__btn ui-modal__btn--' + variant + '"></button>'
        ).text(action.text || "OK");

        $btn.on("click", function () {
          var keepOpen = false;
          if (typeof action.onClick === "function") {
            keepOpen = action.onClick() === false;
          }
          // closeOnClick por defecto true, salvo que onClick retorne false explícitamente
          if (action.closeOnClick !== false && !keepOpen) {
            api.close();
          }
        });

        $actions.append($btn);
      });

      $actions.toggle(actions.length > 0);

      onCloseCallback = typeof opts.onClose === "function" ? opts.onClose : null;

      $root.addClass("is-open").attr("aria-hidden", "false");
      $("body").addClass("ui-modal-locked");

      // Foco al primer botón disponible (mejor accesibilidad)
      var $focusTarget = $actions.find("button").first();
      if (!$focusTarget.length) $focusTarget = $root.find(".ui-modal__close");
      $focusTarget.trigger("focus");
    },

    /** Cierra el modal y limpia callbacks. */
    close: function () {
      var $root = $(ROOT_SELECTOR);
      if (!$root.length || !$root.hasClass("is-open")) return;

      $root.removeClass("is-open").attr("aria-hidden", "true");
      $("body").removeClass("ui-modal-locked");

      var cb = onCloseCallback;
      onCloseCallback = null;
      if (typeof cb === "function") cb();
    },

    /** Diálogo de confirmación con botones Aceptar / Cancelar. */
    confirm: function (opts) {
      opts = opts || {};
      api.open({
        title: opts.title || "¿Confirmar?",
        body:  opts.message || "",
        actions: [
          {
            text: opts.cancelText || "Cancelar",
            variant: "ghost",
            onClick: opts.onCancel
          },
          {
            text: opts.confirmText || "Aceptar",
            variant: opts.variant || "primary",
            onClick: opts.onConfirm
          }
        ]
      });
    },

    /** Diálogo informativo con un único botón. */
    alert: function (opts) {
      opts = opts || {};
      api.open({
        title: opts.title || "Aviso",
        body:  opts.message || "",
        actions: [
          {
            text: opts.buttonText || "Aceptar",
            variant: "primary"
          }
        ]
      });
    }
  };

  // Cierre por tecla Escape (global, una sola vez)
  $(document).on("keydown.uiModal", function (e) {
    if (e.key === "Escape") {
      var $root = $(ROOT_SELECTOR);
      if ($root.length && $root.hasClass("is-open")) api.close();
    }
  });

  // Exponemos el API en jQuery
  $.uiModal = api;
})(window.jQuery);
