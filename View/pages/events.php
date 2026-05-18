<!doctype html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

require_once __DIR__ . '/../../Config/Database.php';

$eventos = [];
$dbError = false;

$sort = isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'DESC' : 'ASC';
$q        = isset($_GET['q'])        ? trim((string) $_GET['q'])        : '';
$category = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
$date     = isset($_GET['date'])     ? trim((string) $_GET['date'])     : '';

$categorias = [
  'Arte y Cultura',
  'Deporte',
  'Tecnología',
  'Político',
  'Corporativo',
  'Ocio',
  'Social',
];

$hasFilters = ($q !== '' || $category !== '' || $date !== '');

try {
  $database = new Database('localhost', 'forever_events', 'root', '');
  $conn = $database->getConexion();

  $sql = "SELECT id, titulo, categoria, fecha, hora, ciudad, imagen_portada
          FROM eventos
          WHERE 1=1";
  $params = [];

  if ($q !== '') {
    $sql .= " AND (titulo LIKE :q_titulo OR ciudad LIKE :q_ciudad OR categoria LIKE :q_categoria)";
    $like = '%' . $q . '%';
    $params[':q_titulo']    = $like;
    $params[':q_ciudad']    = $like;
    $params[':q_categoria'] = $like;
  }
  if ($category !== '' && in_array($category, $categorias, true)) {
    $sql .= " AND categoria = :category";
    $params[':category'] = $category;
  }
  if ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $sql .= " AND fecha = :date";
    $params[':date'] = $date;
  }

  $sql .= " ORDER BY fecha $sort, hora $sort";

  $stmt = $conn->prepare($sql);
  $stmt->execute($params);
  $eventos = $stmt->fetchAll();
} catch (Exception $e) {
  error_log('Error cargando eventos: ' . $e->getMessage());
  $dbError = true;
}
?>

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
          <a href="events.php" aria-current="page">Eventos</a>
          <a href="aboutUs.php">Sobre Nosotros</a>
          <?php if ($_SESSION['user_type'] == 1): ?>
            <a href="createEvent.php">Crear Evento</a>
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
              placeholder="Buscar eventos"
              value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
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
  <script src="../Assets/js/navbar.js" defer></script>
  <script src="../Assets/vendor/jquery-3.7.1.min.js" defer></script>
  <script src="../Assets/js/imageHover.js" defer></script>
  <section class="events-page">
    <div class="container">
      <div class="page-hero">
        <span class="hero-tag">Eventos en directo</span>
        <h1 class="page-title">Explora Eventos</h1>
        <p class="page-subtitle">
          Descubre experiencias únicas y únete a los mejores eventos cerca de ti.
        </p>
      </div>

      <form class="filters-bar" method="GET" action="events.php" role="search">
        <input
          type="search"
          name="q"
          placeholder="Buscar por título, ciudad o categoría..."
          class="filter-search"
          value="<?= htmlspecialchars($q) ?>" />

        <select class="filter-select filter-category" name="category" aria-label="Filtrar por categoría">
          <option value="">Todas las categorías</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <input
          type="date"
          class="filter-date"
          name="date"
          aria-label="Filtrar por fecha"
          value="<?= htmlspecialchars($date) ?>" />

        <select class="filter-select filter-category" name="sort" aria-label="Ordenar por fecha">
          <option value="asc" <?= $sort === 'ASC'  ? 'selected' : '' ?>>Más recientes primero</option>
          <option value="desc" <?= $sort === 'DESC' ? 'selected' : '' ?>>Más antiguos primero</option>
        </select>
        <div class="filter-actions">
          <button type="submit" class="filter-apply">
            <i class="fa-solid fa-sliders"></i>
            Aplicar
          </button>
          <?php if ($hasFilters): ?>
            <a href="events.php" class="filter-clear">
              <i class="fa-solid fa-xmark"></i>
              Limpiar
            </a>
          <?php endif; ?>
        </div>
      </form>

      <?php if (!$dbError): ?>
        <p class="results-count">
          <?php if ($hasFilters): ?>
            <strong><?= count($eventos) ?></strong>
            <?= count($eventos) === 1 ? 'evento encontrado' : 'eventos encontrados' ?>
          <?php else: ?>
            <strong><?= count($eventos) ?></strong>
            <?= count($eventos) === 1 ? 'evento disponible' : 'eventos disponibles' ?>
          <?php endif; ?>
        </p>
      <?php endif; ?>

      <?php if ($dbError): ?>
        <div class="flash flash-error" role="alert">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>No se pudieron cargar los eventos. Inténtalo más tarde.</span>
        </div>
      <?php elseif (empty($eventos)): ?>
        <div class="empty-state">
          <i class="fa-regular fa-calendar-xmark"></i>
          <?php if ($hasFilters): ?>
            <p>No hay eventos que coincidan con tu búsqueda.</p>
            <a href="events.php" class="filter-clear">Quitar filtros</a>
          <?php else: ?>
            <p>Todavía no hay eventos disponibles.</p>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="event-grid">
          <?php foreach ($eventos as $evento): ?>
            <article class="event-card" tabindex="0">
              <a href="eventDetails.php?id=<?php echo (int) $evento['id']; ?>">
                <div class="event-image-wrapper">
                  <?php
                    $hoverFecha  = date('d/m/Y', strtotime($evento['fecha']));
                    $hoverHora   = date('H:i', strtotime($evento['hora']));
                    $hoverMsg    = $hoverFecha . ' · ' . $hoverHora . 'h · ' . $evento['ciudad'];
                  ?>
                  <img
                    src="<?php echo !empty($evento['imagen_portada']) ? htmlspecialchars($evento['imagen_portada']) : '../Assets/img/images/events.jpg'; ?>"
                    alt="Imagen del evento <?php echo htmlspecialchars($evento['titulo']); ?>"
                    class="event-image"
                    data-hover-title="<?php echo htmlspecialchars($evento['titulo']); ?>"
                    data-hover-msg="<?php echo htmlspecialchars($hoverMsg); ?>" />
                </div>

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

                  <a href="eventDetails.php?id=<?php echo (int) $evento['id']; ?>" class="btn btn-primary">Ver Detalles</a>
              </a>
        </div>
        </a>
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