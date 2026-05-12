<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión | Forever Events</title>

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;700;800&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/pages/login.css" />
</head>

<body>
  <main class="auth">
    <!-- Branding compacto -->
    <section class="auth-brand">
      <a
        class="brand-logo"
        aria-label="Forever Events inicio">
        <img
          src="../Assets/img/logoForever.png"
          alt="Logotipo de Forever Events" />
      </a>
      <span class="hero-tag">Forever Events</span>
      <h1>Bienvenido de vuelta.</h1>
      <p>Gestiona tus eventos con control total y precisión profesional.</p>
    </section>

    <!-- Login -->
    <section class="auth-card" role="main" aria-labelledby="login-title">
      <header class="auth-header">
        <h2 id="login-title">Iniciar sesión</h2>
        <span>Accede a tu panel seguro</span>
      </header>

      <form action="../../Controller/userController.php" method="post" class="auth-form" novalidate>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'credenciales'): ?>
          <p style="color: red;">Email o contraseña incorrectos</p>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'missing-data'): ?>
          <p style="color: red;">Por favor, completa todos los campos</p>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
          <p style="color: green;">Cuenta eliminada correctamente</p>
        <?php endif; ?>

        <div class="form-group">
          <label for="user">Correo electrónico</label>
          <input
            type="email"
            id="user"
            name="user"
            placeholder="ejemplo@correo.com"
            autocomplete="email"
            required />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <div class="input-wrapper">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Introduce tu contraseña"
              autocomplete="current-password"
              required />
            <button
              type="button"
              class="toggle-password"
              aria-label="Mostrar contraseña"
              aria-controls="password"
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

        <div class="auth-options">
          <a href="forgotPassword.php">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" name="login" class="primary-btn">Iniciar sesión</button>
      </form>

      <footer class="auth-footer">
        <span>¿No tienes cuenta?</span>
        <a href="register.php">Crear cuenta</a>
      </footer>
    </section>
  </main>
  <script src="../Assets/js/hidePassword.js"></script>
</body>

</html>