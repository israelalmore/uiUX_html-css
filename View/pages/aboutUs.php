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

  <title>Sobre Nosotros | Forever Events</title>
  <meta
    name="description"
    content="Conoce al equipo detrás de Forever Events: estudiantes de DAW con la misión de conectar comunidades a través de eventos." />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;700;800&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/aboutUs.css" />
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
          <a href="aboutUs.php" aria-current="page">Sobre Nosotros</a>
          <?php if ($_SESSION['user_type'] == 1): ?>
            <a href="createEvent.php">Crear Evento</a>
            <a href="myEvents.php"> Mis Eventos</a>
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
    <section class="about-hero background-image">
      <div class="hero-content title">
        <span class="hero-tag">Forever Events</span>
        <h1>Hecho por estudiantes,<br />pensado para todos.</h1>
        <h2>Construimos la forma más sencilla de descubrir y crear eventos.</h2>
      </div>
    </section>

    <section class="who-strip">
      <div class="container who-strip-inner">
        <div class="who-badge">
          <i class="fa-solid fa-graduation-cap"></i>
          <span>Estudiantes DAW</span>
        </div>
        <div class="who-badge">
          <i class="fa-solid fa-code"></i>
          <span>Full-stack</span>
        </div>
        <div class="who-badge">
          <i class="fa-solid fa-lightbulb"></i>
          <span>Innovación</span>
        </div>
        <div class="who-badge">
          <i class="fa-solid fa-people-group"></i>
          <span>Comunidad</span>
        </div>
      </div>
    </section>

    <section class="vmv-section">
      <div class="container">
        <h3 class="section-title">Hacia dónde vamos</h3>

        <div class="vmv-grid">
          <article class="vmv-card">
            <span class="vmv-num">01</span>
            <div class="vmv-icon"><i class="fa-solid fa-eye"></i></div>
            <h4>Visión</h4>
            <p>Conectar comunidades a través de los eventos que las mueven.</p>
          </article>

          <article class="vmv-card vmv-card--accent">
            <span class="vmv-num">02</span>
            <div class="vmv-icon"><i class="fa-solid fa-bullseye"></i></div>
            <h4>Misión</h4>
            <p>Un único lugar para descubrir, crear y gestionar eventos locales.</p>
          </article>

          <article class="vmv-card">
            <span class="vmv-num">03</span>
            <div class="vmv-icon"><i class="fa-solid fa-heart"></i></div>
            <h4>Propósito</h4>
            <p>Que nadie se pierda lo que está pasando a su alrededor.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="values-section">
      <div class="container">
        <h3 class="section-title">Nuestros valores</h3>
        <p class="values-lead">Cuatro principios que guían cada decisión.</p>

        <div class="values-grid">
          <div class="value-tile">
            <div class="value-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h5>Confianza</h5>
            <p>Organizadores verificados.</p>
          </div>
          <div class="value-tile">
            <div class="value-icon"><i class="fa-solid fa-circle-info"></i></div>
            <h5>Transparencia</h5>
            <p>Información clara, sin sorpresas.</p>
          </div>
          <div class="value-tile">
            <div class="value-icon"><i class="fa-solid fa-user-check"></i></div>
            <h5>Tú primero</h5>
            <p>Iteramos con tu feedback.</p>
          </div>
          <div class="value-tile">
            <div class="value-icon"><i class="fa-solid fa-bolt"></i></div>
            <h5>Fiabilidad</h5>
            <p>Siempre disponible.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="final-cta">
      <div class="container">
        <h2>Forma parte de algo nuevo.</h2>
        <a href="events.php" class="btn">Explorar Eventos</a>
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