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
  <link rel="stylesheet" href="../Assets/css/pages/events.css" />
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
  <script>
    const header = document.querySelector(".nav-bar");
    const mobileNavIcon = document.querySelector(".mobile-nav-icon");
    mobileNavIcon.addEventListener("click", () => {
      header.classList.toggle("nav-active");
    });

    function deleteEvent(id) {
      if (confirm('¿Estás seguro de que quieres eliminar este evento? Esta acción no se puede deshacer.')) {
        fetch(`../../Controller/eventController.php?id=${id}`, {
            method: 'DELETE'
          })
          .then(response => {
            if (response.status === 204) {
              window.location.href = 'myEvents.php?deleted=1';
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
  </script>

  <section class="events-page">
    <div class="container">
      <?php if ($dbError): ?>
        <div class="flash flash-error" role="alert">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>No se pudo cargar el evento. Inténtalo más tarde.</span>
        </div>
      <?php else: ?>
        <article class="event-card">
          <img
            src="<?php echo !empty($evento['imagen_portada']) ? htmlspecialchars($evento['imagen_portada']) : '../Assets/img/images/events.jpg'; ?>"
            alt="Imagen del evento <?php echo htmlspecialchars($evento['titulo']); ?>"
            class="event-image" />
          <div class="event-content">
            <div class="event-header">
              <span class="event-category"><?php echo htmlspecialchars($evento['categoria']); ?></span>
            </div>
            <h1 class="event-title"><?php echo htmlspecialchars($evento['titulo']); ?></h1>
            <p class="event-date">
              <?php
              $fechaFmt = date('d/m/Y', strtotime($evento['fecha']));
              $horaFmt  = date('H:i', strtotime($evento['hora']));
              echo htmlspecialchars($fechaFmt . ' | ' . $horaFmt . 'h');
              ?>
            </p>
            <p><?php echo nl2br(htmlspecialchars($evento['descripcion'])); ?></p>
            <div class="event-stats">
              <i class="fa-solid fa-location-dot"></i>
              <?php echo htmlspecialchars($evento['direccion'] . ', ' . $evento['codigo_postal'] . ' ' . $evento['ciudad']); ?>
            </div>
            <div class="event-stats">
              <i class="fa-solid fa-envelope"></i>
              <a href="mailto:<?php echo htmlspecialchars($evento['email']); ?>">
                <?php echo htmlspecialchars($evento['email']); ?>
              </a>
            </div>
            <?php if (!empty($evento['organizador_nombre'])): ?>
              <div class="event-stats">
                <i class="fa-solid fa-user"></i>
                Organiza: <?php echo htmlspecialchars(trim($evento['organizador_nombre'] . ' ' . ($evento['organizador_apellido1'] ?? ''))); ?>
              </div>
            <?php endif; ?>
            <a href="events.php" class="btn btn-primary">Volver</a>
            <?php if ($isOrganizer): ?>
              <button class="btn btn-secondary" onclick="deleteEvent(<?php echo $evento['id']; ?>)">Eliminar Evento</button>
            <?php endif; ?>
          </div>
        </article>
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