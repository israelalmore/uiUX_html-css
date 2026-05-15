<!doctype html>
<html lang="es">

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ((int) $_SESSION['user_type'] !== 1) {
  header('Location: landingPage.php');
  exit();
}

require_once __DIR__ . '/../../Config/Database.php';

$eventos = [];
$dbError = false;

try {
  $database = new Database('localhost', 'forever_events', 'root', '');
  $conn = $database->getConexion();

  $stmt = $conn->prepare(
    "SELECT id, titulo, descripcion, categoria, fecha, hora, ciudad, imagen_portada
     FROM eventos
     WHERE organizador_id = :organizador
     ORDER BY fecha DESC, hora DESC"
  );
  $stmt->bindValue(':organizador', (int) $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();
  $eventos = $stmt->fetchAll();
} catch (Exception $e) {
  error_log('Error cargando mis eventos: ' . $e->getMessage());
  $dbError = true;
}

$success = $_GET['success'] ?? '';
$createdId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$deleted = $_GET['deleted'] ?? '';
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Eventos | Forever Events</title>
  <meta
    name="description"
    content="Gestiona los eventos que has creado en Forever Events." />

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
  <link rel="stylesheet" href="../Assets/css/pages/myEvents.css" />
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
          <a href="aboutUs.php">Sobre Nosotros</a>
          <a href="createEvent.php">Crear Evento</a>
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

          <form action="../../Controller/userController.php" method="POST" class="btn-login">
            <button type="submit" name="delete" class="btn-login">Cerrar Sesión</button>
          </form>
          <a href="profile.php" class="btn-profile">
            <img src="../Assets/img/icons/account.png" alt="Perfil de Usuario" />
            <span>Mi Perfil</span>
          </a>
        </div>
      </nav>
    </div>
  </header>
  <script src="../Assets/js/navbar.js" defer></script>
  <script>
    function deleteEvent(id) {
      if (confirm('¿Estás seguro de que quieres eliminar este evento? Esta acción no se puede deshacer.')) {
        fetch(`../../Controller/eventController.php?id=${id}`, {
            method: 'DELETE'
          })
          .then(response => {
            if (response.status === 204) {
              const card = document.querySelector(`[data-event-id="${id}"]`);
              if (card) card.remove();
              showFlash('Evento eliminado correctamente.', 'success');
            } else if (response.status === 404) {
              alert('El evento no fue encontrado.');
            } else if (response.status === 403) {
              alert('No tienes permisos para eliminar este evento.');
            } else {
              alert('Error al eliminar el evento.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión al eliminar el evento.');
          });
      }
    }

    function showFlash(message, type) {
      const flash = document.createElement('div');
      flash.className = `flash flash-${type}`;
      flash.setAttribute('role', type === 'error' ? 'alert' : 'status');
      flash.innerHTML = `<i class="fa-solid fa-circle-check"></i><span>${message}</span>`;
      document.body.insertBefore(flash, document.body.firstChild);
      setTimeout(() => flash.remove(), 5000);
    }
  </script>

  <?php if ($success === 'created'): ?>
    <div class="flash flash-success" role="status">
      <i class="fa-solid fa-circle-check"></i>
      <span>
        ¡Evento creado correctamente<?php echo $createdId > 0 ? ' (ID #' . $createdId . ')' : ''; ?>!
      </span>
    </div>
  <?php endif; ?>

  <?php if ($deleted === '1'): ?>
    <div class="flash flash-success" role="status">
      <i class="fa-solid fa-circle-check"></i>
      <span>Evento eliminado correctamente.</span>
    </div>
  <?php endif; ?>

  <?php if ($dbError): ?>
    <div class="flash flash-error" role="alert">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <span>No se pudieron cargar tus eventos. Inténtalo más tarde.</span>
    </div>
  <?php endif; ?>

  <section class="events-page">
    <div class="container">
      <div class="page-hero">
        <span class="hero-tag">Tu gestión</span>
        <h1 class="page-title">Mis Eventos</h1>
        <p class="page-subtitle">
          Aquí están todos los eventos que has creado.
        </p>
      </div>

      <?php if (empty($eventos) && !$dbError): ?>
        <div class="empty-state">
          <i class="fa-regular fa-calendar-plus"></i>
          <p>Todavía no has creado ningún evento.</p>
          <a href="createEvent.php" class="btn btn-primary">Crear mi primer evento</a>
        </div>
      <?php else: ?>
        <div class="event-grid">
          <?php foreach ($eventos as $evento): ?>
            <article class="event-card" tabindex="0" data-event-id="<?php echo $evento['id']; ?>">
              <img
                src="<?php echo !empty($evento['imagen_portada']) ? htmlspecialchars($evento['imagen_portada']) : '../Assets/img/images/events.jpg'; ?>"
                alt="Imagen del evento <?php echo htmlspecialchars($evento['titulo']); ?>"
                class="event-image" />
              <div class="event-content">
                <div class="event-header">
                  <span class="event-category"><?php echo htmlspecialchars($evento['categoria']); ?></span>
                </div>
                <h2 class="event-title"><?php echo htmlspecialchars($evento['titulo']); ?></h2>
                <p class="event-date">
                  <?php
                  $fechaFmt = date('d/m/Y', strtotime($evento['fecha']));
                  $horaFmt  = date('H:i', strtotime($evento['hora']));
                  echo htmlspecialchars($fechaFmt . ' | ' . $horaFmt . 'h');
                  ?>
                </p>
                <div class="event-stats">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo htmlspecialchars($evento['ciudad']); ?>
                </div>
                <a href="eventDetails.php?id=<?php echo $evento['id']; ?>" class="btn btn-primary">Ver Detalles</a>
                <button class="btn btn-secondary" onclick="deleteEvent(<?php echo $evento['id']; ?>)">Eliminar</button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
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