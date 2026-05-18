<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

require_once __DIR__ . '/../../Config/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
  header('Location: events.php');
  exit();
}

$evento = null;
$dbError = false;

try {
  $database = new Database('localhost', 'forever_events', 'root', '');
  $conn = $database->getConexion();

  $stmt = $conn->prepare(
    "SELECT e.id, e.titulo, e.descripcion, e.categoria, e.fecha, e.hora,
            e.direccion, e.codigo_postal, e.ciudad, e.email,
            e.imagen_portada, e.imagen_ubicacion, e.organizador_id,
            u.nombre AS organizador_nombre,
            u.apellido1 AS organizador_apellido1
     FROM eventos e
     LEFT JOIN usuarios u ON u.id = e.organizador_id
     WHERE e.id = :id"
  );
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $evento = $stmt->fetch();
} catch (Exception $e) {
  error_log('Error cargando evento ' . $id . ': ' . $e->getMessage());
  $dbError = true;
}

$isOrganizer = !$dbError && isset($evento['organizador_id']) && $evento['organizador_id'] == $_SESSION['user_id'];

if (!$evento && !$dbError) {
  header('Location: events.php');
  exit();
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $evento ? htmlspecialchars($evento['titulo']) : 'Detalles'; ?> | Forever Events</title>

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;700;800&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/eventDetails.css" />
</head>

<body>
  <header class="nav-bar">
    <div class="nav-inner">
      <a href="landingPage.php" class="nav-logo" aria-label="Forever Events inicio">
        <img src="../Assets/img/logoForever.png" alt="Logotipo de Forever Events" />
      </a>
      <button class="mobile-nav-icon" aria-label="Abrir menú">
        <i class="fa-solid fa-bars"></i>
      </button>
      <nav class="nav-container" aria-label="Navegación principal">
        <div class="nav-left">
          <a href="landingPage.php">Inicio</a>
          <a href="events.php">Eventos</a>
          <a href="aboutUs.php">Sobre Nosotros</a>
          <?php if ((int) ($_SESSION['user_type'] ?? 0) === 1): ?>
            <a href="createEvent.php">Crear Evento</a>
            <a href="myEvents.php">Mis Eventos</a>
          <?php endif; ?>
        </div>
        <div class="nav-right">
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
  <script src="../Assets/vendor/jquery-3.7.1.min.js"></script>
  <script src="../Assets/js/modal.js"></script>
  <script>
    function deleteEvent(id) {
      $.uiModal.confirm({
        title: 'Eliminar evento',
        message: '¿Estás seguro de que quieres eliminar este evento? Esta acción no se puede deshacer.',
        confirmText: 'Eliminar',
        cancelText: 'Cancelar',
        variant: 'danger',
        onConfirm: function () {
          fetch(`../../Controller/eventController.php?id=${id}`, {
              method: 'DELETE'
            })
            .then(response => {
              if (response.status === 204) {
                window.location.href = 'myEvents.php?deleted=1';
              } else if (response.status === 404) {
                $.uiModal.alert({ title: 'No encontrado', message: 'El evento no fue encontrado.' });
              } else if (response.status === 403) {
                $.uiModal.alert({ title: 'Sin permisos', message: 'No tienes permisos para eliminar este evento.' });
              } else {
                $.uiModal.alert({ title: 'Error', message: 'Error al eliminar el evento.' });
              }
            })
            .catch(error => {
              console.error('Error:', error);
              $.uiModal.alert({ title: 'Error de conexión', message: 'Error de conexión al eliminar el evento.' });
            });
        }
      });
    }
  </script>

  <section class="event-details-page">
    <div class="container">
      <?php if ($dbError): ?>
        <div class="flash flash-error" role="alert">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>No se pudo cargar el evento. Inténtalo más tarde.</span>
        </div>
      <?php else: ?>
        <?php
          $fechaFmt = date('d/m/Y', strtotime($evento['fecha']));
          $horaFmt  = date('H:i', strtotime($evento['hora']));
          $cover    = !empty($evento['imagen_portada']) ? $evento['imagen_portada'] : '../Assets/img/images/events.jpg';
          $mapImg   = !empty($evento['imagen_ubicacion']) ? $evento['imagen_ubicacion'] : '../Assets/img/mapa.png';
          $organizador = trim(($evento['organizador_nombre'] ?? '') . ' ' . ($evento['organizador_apellido1'] ?? ''));
        ?>

        <a href="events.php" class="event-back">
          <i class="fa-solid fa-arrow-left"></i>
          <span>Volver a eventos</span>
        </a>

        <header class="event-hero">
          <img
            src="<?php echo htmlspecialchars($cover); ?>"
            alt="Imagen del evento <?php echo htmlspecialchars($evento['titulo']); ?>"
            class="event-hero__img" />
          <div class="event-hero__overlay"></div>
          <div class="event-hero__content">
            <span class="event-hero__category">
              <i class="fa-solid fa-tag"></i>
              <?php echo htmlspecialchars($evento['categoria']); ?>
            </span>
            <h1 class="event-hero__title"><?php echo htmlspecialchars($evento['titulo']); ?></h1>
            <div class="event-hero__meta">
              <span><i class="fa-solid fa-calendar-days"></i> <?php echo htmlspecialchars($fechaFmt); ?></span>
              <span><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($horaFmt); ?>h</span>
              <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($evento['ciudad']); ?></span>
            </div>
          </div>
        </header>

        <div class="event-layout">
          <main>
            <section class="event-card-block">
              <h2 class="event-card-block__title">Sobre el evento</h2>
              <p class="event-description"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
            </section>

            <section class="event-card-block">
              <h2 class="event-card-block__title">Ubicación</h2>
              <div class="event-location-grid">
                <img
                  src="<?php echo htmlspecialchars($mapImg); ?>"
                  alt="Ubicación del evento"
                  class="event-location-img" />
                <p class="event-location-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <span>
                    <?php echo htmlspecialchars($evento['direccion']); ?><br>
                    <?php echo htmlspecialchars($evento['codigo_postal'] . ' ' . $evento['ciudad']); ?>
                  </span>
                </p>
              </div>
            </section>
          </main>

          <aside class="event-aside">
            <section class="event-card-block">
              <h2 class="event-card-block__title">Detalles</h2>
              <ul class="event-info-list">
                <li>
                  <span class="info-icon"><i class="fa-solid fa-calendar-days"></i></span>
                  <div class="info-body">
                    <span class="info-label">Fecha</span>
                    <span class="info-value"><?php echo htmlspecialchars($fechaFmt); ?></span>
                  </div>
                </li>
                <li>
                  <span class="info-icon"><i class="fa-solid fa-clock"></i></span>
                  <div class="info-body">
                    <span class="info-label">Hora</span>
                    <span class="info-value"><?php echo htmlspecialchars($horaFmt); ?>h</span>
                  </div>
                </li>
                <li>
                  <span class="info-icon"><i class="fa-solid fa-location-dot"></i></span>
                  <div class="info-body">
                    <span class="info-label">Lugar</span>
                    <span class="info-value">
                      <?php echo htmlspecialchars($evento['direccion'] . ', ' . $evento['codigo_postal'] . ' ' . $evento['ciudad']); ?>
                    </span>
                  </div>
                </li>
                <li>
                  <span class="info-icon"><i class="fa-solid fa-envelope"></i></span>
                  <div class="info-body">
                    <span class="info-label">Contacto</span>
                    <span class="info-value">
                      <a href="mailto:<?php echo htmlspecialchars($evento['email']); ?>">
                        <?php echo htmlspecialchars($evento['email']); ?>
                      </a>
                    </span>
                  </div>
                </li>
                <?php if (!empty($organizador)): ?>
                  <li>
                    <span class="info-icon"><i class="fa-solid fa-user"></i></span>
                    <div class="info-body">
                      <span class="info-label">Organiza</span>
                      <span class="info-value"><?php echo htmlspecialchars($organizador); ?></span>
                    </div>
                  </li>
                <?php endif; ?>
              </ul>
            </section>

            <div class="event-actions">
              <a href="mailto:<?php echo htmlspecialchars($evento['email']); ?>" class="event-btn event-btn--primary">
                <i class="fa-solid fa-paper-plane"></i>
                Contactar al organizador
              </a>
              <a href="events.php" class="event-btn event-btn--ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Ver más eventos
              </a>
              <?php if ($isOrganizer): ?>
                <button type="button" class="event-btn event-btn--danger" onclick="deleteEvent(<?php echo $evento['id']; ?>)">
                  <i class="fa-solid fa-trash"></i>
                  Eliminar evento
                </button>
              <?php endif; ?>
            </div>
          </aside>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <footer class="site-footer">
    <h2 class="footer-title">Forever Events</h2>
    <div class="footer-copy">
      <p>© 2025 Forever Events. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>

</html>