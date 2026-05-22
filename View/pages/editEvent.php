<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ((int) ($_SESSION['user_type'] ?? 0) !== 1) {
  header('Location: landingPage.php');
  exit();
}

require_once __DIR__ . '/../../Config/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
  header('Location: myEvents.php');
  exit();
}

$evento = null;
$dbError = false;

try {
  $database = new Database('localhost', 'forever_events', 'root', '');
  $conn = $database->getConexion();

  $stmt = $conn->prepare(
    "SELECT id, titulo, descripcion, categoria, fecha, hora,
            direccion, codigo_postal, ciudad, email,
            imagen_portada, imagen_ubicacion, organizador_id
     FROM eventos
     WHERE id = :id"
  );
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $evento = $stmt->fetch();
} catch (Exception $e) {
  error_log('Error cargando evento para editar ' . $id . ': ' . $e->getMessage());
  $dbError = true;
}

if (!$evento && !$dbError) {
  header('Location: myEvents.php');
  exit();
}

if ($evento && (int) $evento['organizador_id'] !== (int) $_SESSION['user_id']) {
  header('Location: myEvents.php?error=forbidden');
  exit();
}

$error = $_GET['error'] ?? '';
$errorMessages = [
  'missing-data'  => 'Faltan campos obligatorios. Revisa el formulario.',
  'email-invalid' => 'El correo electrónico no es válido.',
  'date-invalid'  => 'La fecha no tiene un formato válido.',
  'time-invalid'  => 'La hora no tiene un formato válido.',
  'event-exists'  => 'Ya existe otro evento con el mismo título en esa fecha.',
  'db'            => 'Error al guardar los cambios. Inténtalo más tarde.',
];

$categorias = ['Arte y Cultura', 'Deporte', 'Tecnología', 'Político', 'Corporativo', 'Ocio', 'Social'];
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Evento | Forever Events</title>
  <meta name="description" content="Edita los detalles de tu evento en Forever Events." />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/createEvent.css" />
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
          <a href="createEvent.php">Crear Evento</a>
          <a href="myEvents.php">Mis Eventos</a>
        </div>

        <div class="nav-right">
          <form class="search-wrapper" role="search" action="events.php" method="GET">
            <label for="event-search" class="sr-only">Buscar eventos</label>
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="event-search" type="search" name="q" placeholder="Buscar eventos" />
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
  <script src="../Assets/vendor/jquery-3.7.1.min.js" defer></script>
  <script src="../Assets/js/fileUpload.js" defer></script>

  <main class="create-event">
    <div class="container">
      <div class="page-hero">
        <span class="hero-tag">Editar evento</span>
        <h1 class="page-title">Edita tu Evento</h1>
        <p class="page-subtitle">Actualiza los detalles del evento que has creado.</p>
      </div>

      <?php if ($dbError): ?>
        <div class="flash flash-error" role="alert">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>No se pudo cargar el evento. Inténtalo más tarde.</span>
        </div>
      <?php else: ?>
        <?php if ($error && isset($errorMessages[$error])): ?>
          <div class="flash flash-error" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?php echo htmlspecialchars($errorMessages[$error]); ?></span>
          </div>
        <?php endif; ?>

        <form class="event-form" id="editEventForm" action="../../Controller/eventController.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo (int) $evento['id']; ?>" />

          <fieldset class="form-section">
            <legend>Información Básica</legend>
            <div class="form-group">
              <label for="title">Título del evento <span class="required">*</span></label>
              <input type="text" id="title" name="title"
                value="<?php echo htmlspecialchars($evento['titulo']); ?>"
                placeholder="Introduce el título del evento" required />
            </div>
            <div class="form-group">
              <label for="description">Descripción <span class="required">*</span></label>
              <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="category">Categoría <span class="required">*</span></label>
              <select id="category" name="category" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?php echo htmlspecialchars($cat); ?>"
                    <?php echo $cat === $evento['categoria'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>Fecha y Hora</legend>
            <div class="form-row">
              <div class="form-group">
                <label for="event-date">Fecha <span class="required">*</span></label>
                <input type="date" id="event-date" name="event_date"
                  value="<?php echo htmlspecialchars($evento['fecha']); ?>" required />
              </div>
              <div class="form-group">
                <label for="event-time">Hora <span class="required">*</span></label>
                <input type="time" id="event-time" name="event_time"
                  value="<?php echo htmlspecialchars(substr($evento['hora'], 0, 5)); ?>" required />
              </div>
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>Ubicación</legend>
            <div class="form-group">
              <label for="location">Dirección <span class="required">*</span></label>
              <input type="text" id="location" name="location"
                value="<?php echo htmlspecialchars($evento['direccion']); ?>"
                placeholder="Introduce la ubicación" required />
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="postal-code">Código postal <span class="required">*</span></label>
                <input type="text" id="postal-code" name="postal_code"
                  value="<?php echo htmlspecialchars($evento['codigo_postal']); ?>"
                  placeholder="Ej: 28001" required />
              </div>
              <div class="form-group">
                <label for="city">Ciudad <span class="required">*</span></label>
                <input type="text" id="city" name="city"
                  value="<?php echo htmlspecialchars($evento['ciudad']); ?>"
                  placeholder="Introduce la ciudad" required />
              </div>
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>Contacto</legend>
            <div class="form-group">
              <label for="email">Correo electrónico <span class="required">*</span></label>
              <input type="email" id="email" name="email"
                value="<?php echo htmlspecialchars($evento['email']); ?>"
                placeholder="Introduce un email de contacto" required />
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>Imágenes</legend>
            <p class="page-subtitle" style="margin-top:0;">
              Deja los campos vacíos para conservar las imágenes actuales.
            </p>
            <div class="upload-grid">
              <div class="upload-card">
                <label class="file-upload" for="cover-image">
                  <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                  <span class="file-upload__label">Imagen de portada</span>
                  <span class="file-upload__hint">PNG, JPG · máx 5 MB</span>
                </label>
                <input type="file" id="cover-image" name="cover_image" accept="image/*" />
              </div>
              <div class="upload-card">
                <label class="file-upload" for="location-image">
                  <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                  <span class="file-upload__label">Imagen de ubicación</span>
                  <span class="file-upload__hint">PNG, JPG · máx 5 MB</span>
                </label>
                <input type="file" id="location-image" name="location_image" accept="image/*" />
              </div>
            </div>
          </fieldset>

          <div class="edit-actions">
            <button type="submit" name="update" value="1" class="submit-btn">Guardar Cambios</button>
            <a href="myEvents.php" class="submit-btn submit-btn--cancel">Cancelar</a>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </main>

  <footer class="site-footer">
    <h2 class="footer-title">Forever Events</h2>
    <div class="footer-copy">
      <p>© 2025 Forever Events. Todos los derechos reservados.</p>
    </div>
  </footer>

  <style>
    .edit-actions {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-top: 1rem;
    }
    .edit-actions .submit-btn {
      flex: 1 1 auto;
      min-width: 180px;
    }
    .submit-btn--cancel {
      background: transparent !important;
      border: 1.5px solid rgba(255, 255, 255, 0.25) !important;
      color: #fff !important;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .submit-btn--cancel:hover {
      background: rgba(255, 255, 255, 0.08) !important;
      border-color: rgba(255, 255, 255, 0.4) !important;
    }
  </style>
</body>

</html>
