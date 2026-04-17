<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="../Assets/css/pages/register.css" />
  <link rel="stylesheet" href="../Assets/css/main.css" />
  <link rel="stylesheet" href="../Assets/css/components/babyNavBar.css" />
</head>

<body>
  <header class="nav-bar-login">
    <a href="landingPage.php" aria-label="Forever Events inicio">
      <img
        class="logo"
        src="../Assets/img/logoForever.png"
        alt="Logotipo de Forever Events" />
    </a>
  </header>
  <main class="register">
    <section class="register-hero">
      <h1>Crear Cuenta</h1>
      <p>
        Accede a Forever Events y empieza a gestionar tus eventos con control
        total.
      </p>
    </section>
    <section
      class="register-card"
      role="main"
      aria-labelledby="register-title">
      <form action="../../Controller/userController.php" method="POST">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'passwords'): ?>
          <p style="color: red;">Las contraseñas no coinciden</p>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'email-exists'): ?>
          <p style="color: red;">El email ya está registrado</p>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'missing-data'): ?>
          <p style="color: red;">Por favor, completa todos los campos requeridos</p>
        <?php endif; ?>
        <div class="form-block">
          <h2 id="register-title">Información personal</h2>

          <div class="form-row">
            <div class="field">
              <label for="name">Nombre</label>
              <input id="name" name="name" type="text" required />
            </div>

            <div class="field">
              <label for="surname1">Primer apellido</label>
              <input id="surname1" name="surname1" type="text" required />
            </div>
          </div>

          <div class="field">
            <label for="surname2">Segundo apellido</label>
            <input id="surname2" name="surname2" type="text" />
          </div>

          <div class="field">
            <label for="date">Fecha de nacimiento</label>
            <input id="date" name="date" type="date" required />
          </div>
        </div>

        <div class="form-block">
          <h2>Tipo de Usuario</h2>

          <div class="field">
            <label for="user_type"> Tipo de Usuario</label>
            <select id="user_type" name="user_type">
              <option value="1">Gestor</option>
              <option value="2">Usuario</option>
            </select>
          </div>
        </div>

        <div class="form-block">
          <h2>Contacto</h2>

          <div class="field">
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" required />
          </div>

          <div class="field">
            <label for="telephone">Teléfono</label>
            <input id="telephone" name="telephone" type="tel" required />
          </div>

        </div>

        <div class="form-block">
          <h2>Identificación</h2>

          <div class="field">
            <label for="type_doc">Tipo de documento</label>
            <select id="type_doc" name="type_doc" required>
              <option value="1">DNI</option>
              <option value="2">NIE</option>
              <option value="3">NIF</option>
            </select>
          </div>

          <div class="field">
            <label for="document">Documento</label>
            <input id="document" name="document" type="text" required />
          </div>
        </div>

        <div class="form-block">
          <h2>Seguridad</h2>

          <div class="field">
            <label for="pass">Clave de acceso</label>
            <div class="input-wrapper">
              <input id="pass" name="password" type="password" required />
              <button
                type="button"
                class="toggle-password"
                aria-label="Mostrar contraseña"
                aria-controls="pass"
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

          <div class="field">
            <label for="pass2">Repetir clave</label>
            <div class="input-wrapper">
              <input id="pass2" name="password2" type="password" required />
              <button
                type="button"
                class="toggle-password"
                aria-label="Mostrar contraseña"
                aria-controls="pass2"
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

        <div class="form-actions">
          <button name="create" type="submit" class="primary-btn">Crear cuenta</button>
        </div>
      </form>
    </section>
  </main>
  <script src="../Assets/js/hidePassword.js"></script>
</body>

</html>