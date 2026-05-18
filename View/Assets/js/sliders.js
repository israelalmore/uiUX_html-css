/* ─────────────────────────────────────────────────────────────
   Configuración de sliders Slick (jQuery)

   - Dos sliders independientes con configuración distinta:
     · .concerts-slider   → conciertos (autoplay, foco visual en la imagen)
     · .promoters-slider  → promotores (más slides visibles, ritmo manual)
   - Cada slider define sus propios breakpoints responsive (3+).
   ───────────────────────────────────────────────────────────── */

$(document).ready(iniciarSliders);

function iniciarSliders() {
  // ── Slider de conciertos ──
  // 3 cards desktop, 2 tablet, 1 mobile. Autoplay activado.
  if ($(".concerts-slider").length) {
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
        {
          breakpoint: 1024, // ≤ 1024px (tablet landscape)
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,  // ≤ 768px (tablet portrait)
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        },
        {
          breakpoint: 480,  // ≤ 480px (móvil)
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true
          }
        }
      ]
    });
  }

  // ── Slider de promotores ──
  // Configuración propia: más slides visibles, sin autoplay, navegación manual.
  if ($(".promoters-slider").length) {
    $(".promoters-slider").slick({
      slidesToShow: 4,
      slidesToScroll: 2,
      infinite: true,
      arrows: true,
      dots: false,
      autoplay: false,
      speed: 400,
      responsive: [
        {
          breakpoint: 1200, // ≤ 1200px
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 900,  // ≤ 900px (tablet)
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 600,  // ≤ 600px (móvil)
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true
          }
        }
      ]
    });
  }
}
