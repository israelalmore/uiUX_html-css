<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eventos | Forever Events</title>
  <meta
    name="description"
    content="Descubre, crea y únete a eventos inolvidables. Desde eventos corporativos hasta bodas y celebraciones. ¡Empieza hoy!" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;700;800&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/events.css" />
</head>

<body>
  <header class="nav-bar">
    <div class="nav-inner">
      <a
        href="landingPage.php"
        class="nav-logo"
        aria-label="Forever Events inicio">
        <img
          src="../Assets/img/logoForever.png"
          alt="Logotipo de Forever Events" />
      </a>

      <button class="mobile-nav-icon" aria-label="Abrir menú">
        <i class="fa-solid fa-bars"></i>
      </button>

      <nav class="nav-container" aria-label="Navegación principal">
        <div class="nav-left">
          <a href="landingPage.php">Inicio</a>
          <a href="events.php">Eventos</a>
          <a href="createEvent.php">Crear Eventos</a>
        </div>

        <div class="nav-right">
          <form class="search-wrapper" role="search">
            <label for="event-search" class="sr-only">Buscar eventos</label>
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
              id="event-search"
              type="search"
              placeholder="Buscar eventos" />
          </form>

          <a href="login.php" class="btn-login"> Iniciar Sesión </a>

          <a href="profile.php" class="btn-profile">
            <img src="../Assets/img/icons/account.png" alt="" />
            <span>Mi Perfil</span>
          </a>
        </div>
      </nav>
    </div>
  </header>
  <script>
    const header = document.querySelector(".nav-bar");
    const mobileNavIcon = document.querySelector(".mobile-nav-icon");

    mobileNavIcon.addEventListener("click", () => {
      header.classList.toggle("nav-active");
    });
  </script>
  <section class="events-page">
    <div class="container">
      <h1 class="page-title">Explora Eventos</h1>
      <p class="page-subtitle">
        Descubre experiencias únicas y únete a los mejores eventos cerca de
        ti.
      </p>

      <div class="filters-bar">
        <input
          type="search"
          placeholder="Buscar evento..."
          class="filter-search" />
        <select class="filter-category">
          <option value="">Todas las categorías</option>
          <option>Arte y Cultura</option>
          <option>Deporte</option>
          <option>Tecnología</option>
          <option>Político</option>
          <option>Corporativo</option>
          <option>Ocio</option>
          <option>Social</option>
        </select>
        <input type="date" class="filter-date" />
      </div>

      <div class="event-grid">
        <article class="event-card" tabindex="0">
          <img
            src="../Assets/img/images/eventsV2.jfif"
            alt="Evento de Comedia"
            class="event-image" />
          <div class="event-content">
            <div class="event-header">
              <span class="event-category">Arte y Cultura</span>
              <span class="event-badge">Popular</span>
              <div class="event-rating">
                <i class="fa-solid fa-star"></i> 4.8 (27)
              </div>
            </div>
            <h2 class="event-title">La capital del pecado</h2>
            <p class="event-date">Jueves, 30 de Marzo | 18:00h</p>
            <div class="event-stats">
              <i class="fa-solid fa-users"></i> 300 personas van
            </div>
            <a href="login.php" class="btn btn-primary">Ver Detalles</a>
            <div class="event-actions">
              <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
              <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
            </div>
          </div>
        </article>

        <article class="event-card" tabindex="0">
          <img
            src="../Assets/img/images/events.jpg"
            alt="Concierto de Rock"
            class="event-image" />
          <div class="event-content">
            <div class="event-header">
              <span class="event-category">Música</span>
              <span class="event-badge">Nuevo</span>
              <div class="event-rating">
                <i class="fa-solid fa-star"></i> 4.5 (12)
              </div>
            </div>
            <h2 class="event-title">Rock en la Plaza</h2>
            <p class="event-date">Viernes, 31 de Marzo | 20:00h</p>
            <div class="event-stats">
              <i class="fa-solid fa-users"></i> 450 personas van
            </div>
            <a href="login.php" class="btn btn-primary">Ver Detalles</a>
            <div class="event-actions">
              <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
              <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
            </div>
          </div>
        </article>

        <article class="event-card" tabindex="0">
          <img
            src="../Assets/img/images/eventsV2.jfif"
            alt="Conferencia tecnológica"
            class="event-image" />
          <div class="event-content">
            <div class="event-header">
              <span class="event-category">Tecnología</span>
              <span class="event-badge">Popular</span>
              <div class="event-rating">
                <i class="fa-solid fa-star"></i> 4.9 (33)
              </div>
            </div>
            <h2 class="event-title">Tech Future 2026</h2>
            <p class="event-date">Sábado, 1 de Abril | 09:00h</p>
            <div class="event-stats">
              <i class="fa-solid fa-users"></i> 1200 personas van
            </div>
            <a href="login.php" class="btn btn-primary">Ver Detalles</a>
            <div class="event-actions">
              <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
              <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
            </div>
          </div>
        </article>

        <article class="event-card" tabindex="0">
          <img
            src="../Assets/img/images/events.jpg"
            alt="Torneo deportivo"
            class="event-image" />
          <div class="event-content">
            <div class="event-header">
              <span class="event-category">Deporte</span>
              <span class="event-badge">Nuevo</span>
              <div class="event-rating">
                <i class="fa-solid fa-star"></i> 4.7 (18)
              </div>
            </div>
            <h2 class="event-title">Maratón de Primavera</h2>
            <p class="event-date">Domingo, 2 de Abril | 07:00h</p>
            <div class="event-stats">
              <i class="fa-solid fa-users"></i> 800 personas van
            </div>
            <a href="login.php" class="btn btn-primary">Ver Detalles</a>
            <div class="event-actions">
              <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
              <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <footer class="site-footer">
    <h2 class="footer-title">Forever Events</h2>
    <div class="footer-container">
      <section class="footer-contact">
        <address class="contact-info">
          <p><strong>Contacto</strong></p>
          <p>Teléfono: <a href="tel:+34676676767">+34 676 67 67</a></p>
          <p>
            Email:
            <a href="mailto:contact@foreverevents.com">contact@foreverevents.com</a>
          </p>
          <p>Dirección: Calle Ramón Llull, 67</p>
        </address>
      </section>
      <section class="footer-map">
        <figure>
          <img
            src="../Assets/img/mapa.png"
            alt="Mapa de ubicación de Forever Events en Calle Ramón Llull, 67" />
        </figure>
      </section>
    </div>
    <div class="footer-bottom">
      <nav class="footer-social" aria-label="Redes sociales">
        <h3>Redes Sociales</h3>
        <ul>
          <li>
            <a href="#" aria-label="Instagram Forever Events">
              <img
                src="../Assets/img/icons/instagramLogo.png"
                alt="Instagram Forever Events" />
            </a>
          </li>
          <li>
            <a href="#" aria-label="Facebook Forever Events">
              <img
                src="../Assets/img/icons/facebookLogo.png"
                alt="Facebook Forever Events" />
            </a>
          </li>
          <li>
            <a href="#" aria-label="YouTube Forever Events">
              <img
                src="../Assets/img/icons/youtubeLogo.png"
                alt="YouTube Forever Events" />
            </a>
          </li>
        </ul>
      </nav>
      <nav class="footer-legal" aria-label="Información legal">
        <h3>Políticas</h3>
        <div class="legal-links">
          <ul>
            <li><a href="#">Política de privacidad</a></li>
            <li><a href="#">Aviso Legal</a></li>
          </ul>
          <ul>
            <li><a href="#">Términos y condiciones</a></li>
            <li><a href="#">Cookies</a></li>
          </ul>
        </div>
      </nav>
    </div>
    <div class="footer-copy">
      <p>© 2025 Forever Events. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>

</html>