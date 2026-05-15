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
          <a href="aboutUs.php">Sobre Nosotros</a>
          <?php if ($_SESSION['user_type'] == 1): ?>
            <a href="createEvent.php">Crear Eventos</a>
            <a href="myEvents.php"> Mis Eventos</a>
          <?php endif; ?>
        </div>

        <div class="nav-right">
          <form class="search-wrapper" role="search" action="events.php" method="GET">
            <label for="event-search" class="sr-only">Buscar eventos</label>
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
              id="event-search"
              type="search"
              name="q"
              placeholder="Buscar eventos" />
          </form>

          <form action="../../Controller/userController.php" method="POST">
            <button type="submit" name="delete" class="btn-login">Cerrar Sesión</button>
          </form>
        </div>
      </nav>
    </div>
  </header>

  <script src="../Assets/js/navbar.js" defer></script>

  <main class="profile-wrapper">
    <section class="profile-hero">
      <span class="hero-tag">Mi cuenta</span>
      <h1>Editar perfil</h1>
      <p>Actualiza tu información personal y mantén tu cuenta segura.</p>
    </section>

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
          <?php if ($_GET['error'] === 'missing_data'): ?>
            <p style="color: red;">Por favor, completa todos los campos requeridos</p>
          <?php elseif ($_GET['error'] === 'wrong_password'): ?>
            <p style="color: red;">La contraseña actual es incorrecta</p>
          <?php elseif ($_GET['error'] === 'password_mismatch'): ?>
            <p style="color: red;">Las contraseñas nuevas no coinciden</p>
          <?php endif; ?>
        <?php endif; ?>

        <div class="profile-layout">
          <aside class="profile-avatar">
            <div class="avatar-container">
              <img
                src="<?= !empty($_SESSION['user_avatar']) ? htmlspecialchars($_SESSION['user_avatar']) : '../Assets/img/icons/account.png' ?>"
                alt="Avatar de usuario"
                class="avatar-image" />

              <?php if ($_SESSION['user_type'] == 1): ?>
                <label for="avatar-upload" class="avatar-upload-btn" aria-label="Cambiar foto de perfil">
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

            <h2 class="profile-name" id="profile-title">
              <?= htmlspecialchars(trim(($user['nombre'] ?? '') . ' ' . ($user['apellido1'] ?? ''))) ?: 'Mi perfil' ?>
            </h2>

            <p class="profile-email-display">
              <i class="fa-solid fa-envelope"></i>
              <?= htmlspecialchars($user['email'] ?? '') ?>
            </p>

            <span class="profile-role-badge">
              <?php if ((int)($_SESSION['user_type'] ?? 0) === 1): ?>
                <i class="fa-solid fa-calendar-check"></i> Gestor de eventos
              <?php else: ?>
                <i class="fa-solid fa-user"></i> Cliente
              <?php endif; ?>
            </span>
          </aside>

          <div class="profile-sections">
            <section class="form-section">
              <header class="section-header">
                <i class="fa-solid fa-user-pen"></i>
                <div>
                  <h3>Información personal</h3>
                  <p>Tus datos de identificación.</p>
                </div>
              </header>

              <div class="form-grid">
            <div class="form-field">
              <label for="name">Nombre</label>
              <div class="input-icon">
                <i class="fa-solid fa-user" aria-hidden="true"></i>
                <input
                  id="name"
                  name="name"
                  type="text"
                  placeholder="Ej. María"
                  value="<?= htmlspecialchars($user['nombre']) ?>" />
              </div>
            </div>

            <div class="form-field">
              <label for="surname1">Primer apellido</label>
              <div class="input-icon">
                <i class="fa-solid fa-id-badge" aria-hidden="true"></i>
                <input
                  id="surname1"
                  type="text"
                  name="surname1"
                  placeholder="Ej. García"
                  value="<?= htmlspecialchars($user['apellido1']) ?>" />
              </div>
            </div>

            <div class="form-field">
              <label for="surname2">Segundo apellido</label>
              <div class="input-icon">
                <i class="fa-solid fa-id-badge" aria-hidden="true"></i>
                <input
                  id="surname2"
                  type="text"
                  name="surname2"
                  placeholder="Ej. Pérez"
                  value="<?= htmlspecialchars($user['apellido2'] ?? '') ?>" />
              </div>
            </div>

            <div class="form-field">
              <label for="gender">Género</label>
              <div class="input-icon">
                <i class="fa-solid fa-venus-mars" aria-hidden="true"></i>
                <select id="gender">
                  <option>Hombre</option>
                  <option>Mujer</option>
                  <option>Otro</option>
                </select>
              </div>
            </div>

            <div class="form-field">
              <label for="date">Fecha de nacimiento</label>
              <div class="input-icon">
                <i class="fa-solid fa-cake-candles" aria-hidden="true"></i>
                <input
                  id="date"
                  name="date"
                  type="date"
                  value="<?= htmlspecialchars($user['fecha_nacimiento']) ?>" />
              </div>
            </div>
              </div>
            </section>

            <section class="form-section">
              <header class="section-header">
                <i class="fa-solid fa-address-book"></i>
                <div>
                  <h3>Contacto</h3>
                  <p>Cómo podemos comunicarnos contigo.</p>
                </div>
              </header>

              <div class="form-grid">
            <div class="form-field">
              <label for="telephone">Teléfono</label>
              <div class="input-icon">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <input
                  id="telephone"
                  name="telephone"
                  type="tel"
                  placeholder="Ej. 612 345 678"
                  value="<?= htmlspecialchars($user['telefono']) ?>" />
              </div>
            </div>

            <div class="form-field">
              <label for="document">Documento</label>
              <div class="input-icon">
                <i class="fa-solid fa-id-card" aria-hidden="true"></i>
                <input
                  id="document"
                  type="text"
                  placeholder="Documento"
                  value="<?= htmlspecialchars($user['documento']) ?>"
                  readonly />
              </div>
            </div>

            <div class="form-field full">
              <label for="email">Correo electrónico</label>
              <div class="input-icon">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <input
                  id="email"
                  name="email"
                  type="email"
                  placeholder="ejemplo@correo.com"
                  value="<?= htmlspecialchars($user['email']) ?>" />
              </div>
            </div>
              </div>
            </section>

            <section class="form-section">
              <header class="section-header">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                  <h3>Seguridad</h3>
                  <p>Cambia tu contraseña periódicamente.</p>
                </div>
              </header>

              <div class="form-grid">
            <div class="form-field full">
              <label for="current_password">Contraseña actual</label>
              <div class="input-wrapper input-icon">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                <input
                  type="password"
                  id="current_password"
                  name="current_password"
                  placeholder="Introduce tu contraseña actual"
                  autocomplete="current-password" />
                <button
                  type="button"
                  class="toggle-password"
                  aria-label="Mostrar contraseña"
                  aria-controls="current_password"
                  aria-pressed="false"
                  onclick="togglePassword(this)">
                  <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                  </svg>
                  <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.05 10.05 0 0 1 2.38-3.88M6.53 6.53A9.96 9.96 0 0 1 12 4.5c5 0 9.27 3.11 11 7.5a10.06 10.06 0 0 1-4.07 5.06M9.9 9.9A3 3 0 0 0 12 15a3 3 0 0 0 2.1-5.1M3 3l18 18" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="form-field full">
              <label for="new_password">Nueva contraseña</label>
              <div class="input-wrapper input-icon">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                <input
                  type="password"
                  id="new_password"
                  name="new_password"
                  placeholder="Mínimo 8 caracteres"
                  autocomplete="new-password" />
                <button
                  type="button"
                  class="toggle-password"
                  aria-label="Mostrar contraseña"
                  aria-controls="new_password"
                  aria-pressed="false"
                  onclick="togglePassword(this)">
                  <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                  </svg>
                  <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.05 10.05 0 0 1 2.38-3.88M6.53 6.53A9.96 9.96 0 0 1 12 4.5c5 0 9.27 3.11 11 7.5a10.06 10.06 0 0 1-4.07 5.06M9.9 9.9A3 3 0 0 0 12 15a3 3 0 0 0 2.1-5.1M3 3l18 18" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="form-field full">
              <label for="confirm_password">Confirmar nueva contraseña</label>
              <div class="input-wrapper input-icon">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  placeholder="Repite la nueva contraseña"
                  autocomplete="new-password" />
                <button
                  type="button"
                  class="toggle-password"
                  aria-label="Mostrar contraseña"
                  aria-controls="confirm_password"
                  aria-pressed="false"
                  onclick="togglePassword(this)">
                  <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                  </svg>
                  <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.05 10.05 0 0 1 2.38-3.88M6.53 6.53A9.96 9.96 0 0 1 12 4.5c5 0 9.27 3.11 11 7.5a10.06 10.06 0 0 1-4.07 5.06M9.9 9.9A3 3 0 0 0 12 15a3 3 0 0 0 2.1-5.1M3 3l18 18" />
                  </svg>
                </button>
              </div>
            </div>

              </div>
            </section>

            <div class="form-actions">
              <button type="submit" name="update" class="primary-btn">
                <i class="fa-solid fa-floppy-disk"></i>
                Guardar cambios
              </button>
            </div>
          </div>
        </div>
      </form>

      <div class="danger-zone">
        <header class="danger-header">
          <span class="danger-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </span>
          <div>
            <h3 id="danger-title" class="danger-title">Zona de peligro</h3>
            <p class="danger-description">
              Al eliminar tu cuenta se borrarán todos tus datos de forma permanente. Esta acción no se puede deshacer.
            </p>
          </div>
        </header>

        <form class="danger-form"
          action="../../Controller/userController.php"
          method="POST"
          onsubmit="return confirm('¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">

          <?php if (isset($_GET['error']) && $_GET['error'] === 'delete_wrong_password'): ?>
            <p class="danger-error">La contraseña es incorrecta</p>
          <?php endif; ?>

          <div class="form-field">
            <label for="delete_password">Confirma tu contraseña para continuar</label>
            <div class="input-icon">
              <i class="fa-solid fa-lock" aria-hidden="true"></i>
              <input type="password" id="delete_password" name="delete_password" placeholder="Tu contraseña actual" required />
            </div>
          </div>

          <button type="submit" name="deleteUser" class="danger-btn">
            <i class="fa-solid fa-trash"></i>
            Eliminar cuenta
          </button>
        </form>
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
  <script src="../Assets/js/hidePassword.js"></script>
</body>

</html>