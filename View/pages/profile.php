<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

require_once '../../Config/Database.php';

try {
  $database = new Database('localhost', 'forever_events', 'root', '');
  $conn = $database->getConexion();

  $stmt = $conn->prepare(
    "SELECT nombre, apellido1, apellido2, fecha_nacimiento, email, telefono, documento
     FROM usuarios WHERE id = :id"
  );
  $stmt->bindValue(':id', (int) $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetch();
} catch (PDOException | RuntimeException $e) {
  error_log('Error cargando perfil: ' . $e->getMessage());
  header('Location: login.php?error=db');
  exit();
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Perfil de Usuario | Forever Events</title>
  <meta
    name="description"
    content="Gestiona y actualiza tu perfil de usuario en Forever Events. Información personal, contacto y preferencias." />

  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/profile.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
  <header class="nav-bar">
    <div class="nav-inner">

      <a href="landingPage.php"
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

          <form action="../../Controller/userController.php" method="POST">
            <button type="submit" name="delete" class="btn-login">Cerrar Sesión</button>
          </form>
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

  <main class="profile-wrapper">
    <section class="profile-card" aria-labelledby="profile-title">

      <form
        class="profile-form"
        action="../../Controller/userController.php"
        method="POST"
        enctype="multipart/form-data">

        <?php if (isset($_GET['success'])): ?>
          <p style="color: green;">Datos actualizados correctamente</p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
          <p style="color: red;">Por favor, completa todos los campos requeridos</p>
        <?php endif; ?>

        <div class="profile-layout">
          <div class="profile-avatar">
            <div class="avatar-container">
              <img
                src="<?= !empty($_SESSION['user_avatar']) ? htmlspecialchars($_SESSION['user_avatar']) : '../Assets/img/icons/account.png' ?>"
                alt="Avatar de usuario"
                class="avatar-image" />

              <?php if ($_SESSION['user_type'] == 1): ?>
                <label for="avatar-upload" class="avatar-upload-btn">
                  <i class="fa-solid fa-camera"></i>
                </label>
                <input
                  type="file"
                  id="avatar-upload"
                  name="avatar"
                  class="avatar-input"
                  accept="image/*" />
              <?php endif; ?>
            </div>

            <h2 class="profile-title">Mi perfil</h2>

            <?php if ($_SESSION['user_type'] == 1): ?>
              <p class="avatar-text">Añadir o cambiar foto</p>
            <?php endif; ?>
          </div>

          <div class="form-grid">
            <div class="form-field">
              <label for="name">Nombre</label>
              <input
                id="name"
                name="name"
                type="text"
                placeholder="Nombre de usuario"
                value="<?= htmlspecialchars($user['nombre']) ?>" />
            </div>

            <div class="form-field">
              <label for="surname1">Apellido 1</label>
              <input
                id="surname1"
                type="text"
                name="surname1"
                placeholder="Primer apellido"
                value="<?= htmlspecialchars($user['apellido1']) ?>" />
            </div>

            <div class="form-field">
              <label for="surname2">Apellido 2</label>
              <input
                id="surname2"
                type="text"
                name="surname2"
                placeholder="Segundo apellido"
                value="<?= htmlspecialchars($user['apellido2'] ?? '') ?>" />
            </div>

            <div class="form-field">
              <label for="gender">Género</label>
              <select id="gender">
                <option>Hombre</option>
                <option>Mujer</option>
                <option>Otro</option>
              </select>
            </div>

            <div class="form-field">
              <label for="date">Fecha de nacimiento</label>
              <input
                id="date"
                name="date"
                type="date"
                value="<?= htmlspecialchars($user['fecha_nacimiento']) ?>" />
            </div>

            <div class="form-field">
              <label for="telephone">Teléfono</label>
              <input
                id="telephone"
                name="telephone"
                type="tel"
                placeholder="+34 --"
                value="<?= htmlspecialchars($user['telefono']) ?>" />
            </div>

            <div class="form-field">
              <label for="document">Documento</label>
              <input
                id="document"
                type="text"
                placeholder="Documento"
                value="<?= htmlspecialchars($user['documento']) ?>"
                readonly />
            </div>

            <div class="form-field full">
              <label for="email">Correo electrónico</label>
              <input
                id="email"
                name="email"
                type="email"
                placeholder="mail@gmail.com"
                value="<?= htmlspecialchars($user['email']) ?>" />
            </div>

            <div class="form-field full">
              <label for="password">Contraseña</label>
              <input
                id="password"
                name="password"
                type="password"
                placeholder="********" />
            </div>

            <div class="form-actions full">
              <button type="submit" name="update" class="primary-btn">
                Guardar Cambios
              </button>
            </div>
          </div>
        </div>

      </form>
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
            <a href="mailto:contact@foreverevents.com">
              contact@foreverevents.com
            </a>
          </p>
          <p>Ubicación: Calle Ramón Llull, 67</p>
        </address>
      </section>

      <section class="footer-map">
        <figure>
          <img
            src="../Assets/img/mapa.png"
            alt="Ubicación de Forever Events en Calle Ramón Llull, 67" />
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