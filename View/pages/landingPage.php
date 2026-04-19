<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Forever Events | Tu Plataforma de Eventos Todo en Uno</title>
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
  <link rel="stylesheet" href="../Assets/css/pages/landingpage.css" />
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
          <?php if ($_SESSION['user_type'] == 1): ?>
            <a href="createEvent.php">Crear Eventos</a>
          <?php endif; ?>
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

          <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
          <?php else: ?>
            <form action="../../Controller/userController.php" method="POST">
              <button type="submit" name="delete" class="btn-login">Cerrar Sesión</button>
            </form>
            <a href="profile.php" class="btn-profile">
              <img src="../Assets/img/icons/account.png" alt="Perfil de Usuario" />
              <span>Mi Perfil</span>
            </a>
          <?php endif; ?>
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
  <main>
    <section class="hero-section background-image">
      <div class="hero-content title">
        <h1>Forever Events</h1>
        <h2>Descubre, crea y gestiona eventos memorables.</h2>
        <div class="hero-cta">
          <a href="login.php" class="btn">Crea tu Primer Evento Gratis</a>
        </div>
      </div>
    </section>

    <section class="how-it-works">
      <div class="container">
        <h3 class="section-title">Todo Empieza Aquí</h3>
        <div class="steps">
          <div class="step">
            <div class="step-icon"><i class="fa-solid fa-search"></i></div>
            <h4>1. Descubre</h4>
            <p>
              Explora cientos de eventos públicos por categoría, fecha o
              ubicación.
            </p>
          </div>
          <div class="step">
            <div class="step-icon">
              <i class="fa-solid fa-ticket"></i>
            </div>
            <h4>2. Únete</h4>
            <p>
              Confirma tu asistencia con un solo clic y añádelo a tu
              calendario.
            </p>
          </div>
          <div class="step">
            <div class="step-icon">
              <i class="fa-solid fa-users"></i>
            </div>
            <h4>3. Conecta</h4>
            <p>
              Disfruta de experiencias únicas y conecta con otras personas.
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="featured-events">
      <div class="container">
        <h3 class="section-title">Eventos que te Podrían Gustar</h3>
        <div class="event-grid">
          <article class="event-card">
            <img
              src="../Assets/img/images/LandingPage2.avif"
              alt="Concierto de rock"
              class="event-image" />
            <div class="event-content">
              <div class="event-header">
                <span class="event-category">Música</span>
                <div class="event-rating">
                  <i class="fa-solid fa-star"></i> 4.9 (120)
                </div>
              </div>
              <h4 class="event-title">Noche de Rock Acústico</h4>
              <p class="event-date">Sábado, 25 de Marzo | 21:00h</p>
              <div class="event-stats">
                <i class="fa-solid fa-users"></i> 85 personas van
              </div>
              <a href="register.php" class="btn btn-secondary">RSVP Ahora</a>
              <div class="event-actions">
                <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
                <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
              </div>
            </div>
          </article>
          <article class="event-card">
            <img
              src="../Assets/img/images/landingPage.jpg"
              alt="Evento de Networking"
              class="event-image" />
            <div class="event-content">
              <div class="event-header">
                <span class="event-category">Tecnología</span>
                <div class="event-rating">
                  <i class="fa-solid fa-star"></i> 5.0 (30)
                </div>
              </div>
              <h4 class="event-title">Tech Innovators Meetup</h4>
              <p class="event-date">Jueves, 30 de Marzo | 18:00h</p>
              <div class="event-stats">
                <i class="fa-solid fa-users"></i> 130 personas
              </div>
              <a href="login.php" class="btn btn-secondary">Ver Detalles</a>
              <div class="event-actions">
                <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
                <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
              </div>
            </div>
          </article>
          <article class="event-card">
            <img
              src="../Assets/img/images/landingPage.jpg"
              alt="Evento de Networking"
              class="event-image" />
            <div class="event-content">
              <div class="event-header">
                <span class="event-category">Tecnología</span>
                <div class="event-rating">
                  <i class="fa-solid fa-star"></i> 5.0 (30)
                </div>
              </div>
              <h4 class="event-title">Tech Innovators Meetup</h4>
              <p class="event-date">Jueves, 30 de Marzo | 18:00h</p>
              <div class="event-stats">
                <i class="fa-solid fa-users"></i> 130 personas
              </div>
              <a href="login.php" class="btn btn-secondary">Ver Detalles</a>
              <div class="event-actions">
                <a href="#" aria-label="Añadir a Google Calendar"><i class="fa-brands fa-google"></i></a>
                <a href="#" aria-label="Compartir en Twitter"><i class="fa-brands fa-twitter"></i></a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="features-section">
      <div class="container">
        <h3 class="section-title">Tu Evento, Tus Reglas</h3>
        <div class="features-grid">
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h4>Eventos Públicos y Privados</h4>
            <p>
              Control total sobre quién ve y se une a tus eventos. Ideal para
              reuniones exclusivas o grandes audiencias.
            </p>
          </div>
          <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-bell"></i></div>
            <h4>Notificaciones y Recordatorios</h4>
            <p>
              Tus asistentes recibirán recordatorios automáticos para que
              nadie se pierda el gran día.
            </p>
          </div>
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fa-solid fa-chart-line"></i>
            </div>
            <h4>Estadísticas de Asistencia</h4>
            <p>
              Analiza el engagement y la asistencia a tus eventos para mejorar
              en cada edición.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="testimonials">
      <div class="container">
        <h3 class="section-title">Lo que dicen nuestros usuarios</h3>
        <div class="testimonial-grid">
          <blockquote class="testimonial-card">
            <p>
              "La mejor plataforma para organizar nuestros meetups. Sencilla,
              potente y con un diseño impecable."
            </p>
            <footer>
              <cite>— Ana García, Tech Community Manager</cite>
            </footer>
          </blockquote>
          <blockquote class="testimonial-card">
            <p>
              "Gracias a Forever Events, la organización de mi boda fue mucho
              más fácil. ¡Mis invitados lo amaron!"
            </p>
            <footer>
              <cite>— Carlos Pérez, Novio Feliz</cite>
            </footer>
          </blockquote>
        </div>
      </div>
    </section>

    <section class="final-cta">
      <div class="container">
        <h2>¿Listo para Crear Algo Increíble?</h2>
        <p>
          Únete a miles de creadores y asistentes que ya confían en nosotros.
        </p>
        <a href="register.php" class="btn">Empieza Gratis Ahora</a>
      </div>
    </section>
  </main>

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