<!doctype html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ($_SESSION['user_type'] === 2) {
  header('Location: landingPage.php');
  exit();
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Evento | Forever Events</title>
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
  <link rel="stylesheet" href="../Assets/css/pages/createEvent.css" />
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

          <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
          <?php else: ?>
            <form action="../../Controller/userController.php" method="POST" class="btn-login">
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

  <main class="create-event">
    <div class="container">
      <h1 class="page-title">Crea tu Evento</h1>
      <p class="page-subtitle">
        Organiza experiencias inolvidables, paso a paso.
      </p>

      <form class="event-form" id="createEventForm">
        <fieldset class="form-section">
          <legend>Información Básica</legend>
          <div class="form-group">
            <label for="title">Título del evento <span class="required">*</span></label>
            <input
              type="text"
              id="title"
              name="title"
              placeholder="Introduce el título del evento"
              required />
          </div>
          <div class="form-group">
            <label for="description">Descripción <span class="required">*</span></label>
            <textarea
              id="description"
              name="description"
              rows="4"
              required></textarea>
          </div>
          <div class="form-group">
            <label for="category">Categoría <span class="required">*</span></label>
            <select id="category" name="category" required>
              <option value="">Selecciona una categoría</option>
              <option>Arte y Cultura</option>
              <option>Deporte</option>
              <option>Tecnología</option>
              <option>Político</option>
              <option>Corporativo</option>
              <option>Ocio</option>
              <option>Social</option>
            </select>
          </div>
        </fieldset>

        <fieldset class="form-section">
          <legend>Fecha y Hora</legend>
          <div class="form-row">
            <div class="form-group">
              <label for="event-date">Fecha <span class="required">*</span></label>
              <input type="date" id="event-date" name="event_date" required />
            </div>
            <div class="form-group">
              <label for="event-time">Hora <span class="required">*</span></label>
              <input type="time" id="event-time" name="event_time" required />
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section">
          <legend>Ubicación</legend>
          <div class="form-group">
            <label for="location">Dirección <span class="required">*</span></label>
            <input
              type="text"
              id="location"
              name="location"
              placeholder="Introduce la ubicación"
              required />
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="postal-code">Código postal <span class="required">*</span></label>
              <input
                type="text"
                id="postal-code"
                name="postal_code"
                placeholder="Ej: 28001"
                required />
            </div>
            <div class="form-group">
              <label for="city">Ciudad <span class="required">*</span></label>
              <input
                type="text"
                id="city"
                name="city"
                placeholder="Introduce la ciudad"
                required />
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section">
          <legend>Contacto</legend>
          <div class="form-group">
            <label for="email">Correo electrónico <span class="required">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Introduce un email de contacto"
              required />
          </div>
        </fieldset>

        <fieldset class="form-section">
          <legend>Imágenes</legend>
          <div class="upload-grid">
            <div class="upload-card">
              <label class="file-upload" for="cover-image"><i class="fa fa-cloud-upload"></i> Portada</label>
              <input
                type="file"
                id="cover-image"
                name="cover_image"
                accept="image/*" />
            </div>
            <div class="upload-card">
              <label class="file-upload" for="location-image"><i class="fa fa-cloud-upload"></i> Ubicación</label>
              <input
                type="file"
                id="location-image"
                name="location_image"
                accept="image/*" />
            </div>
          </div>
        </fieldset>

        <button type="submit" class="submit-btn">Crear Evento</button>
      </form>
    </div>
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