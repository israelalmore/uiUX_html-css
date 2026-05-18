/* ─────────────────────────────────────────────────────────────
   Feedback visual al subir imágenes (jQuery)
   - Al seleccionar un archivo, actualiza el label con su nombre.
   - Añade la clase .has-file al label para resaltarlo en verde.
   ───────────────────────────────────────────────────────────── */

$(document).ready(function () {
  $(".upload-card input[type='file']").change(function () {
    var $input = $(this);
    var $label = $("label[for='" + $input.attr("id") + "']");
    var file = $input[0].files && $input[0].files[0];

    if (file) {
      $label.addClass("has-file");
      $label.find(".file-upload__label").text(file.name);
      var sizeKB = (file.size / 1024).toFixed(0);
      $label.find(".file-upload__hint").text("Listo · " + sizeKB + " KB");
    } else {
      $label.removeClass("has-file");
    }
  });
});
