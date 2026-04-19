# 📊 ANÁLISIS DE USABILIDAD - FOREVER EVENTS
## Evaluación según las 10 Heurísticas de Usabilidad de Jakob Nielsen

---

# 1️⃣ VISIBILIDAD DEL ESTADO DEL SISTEMA

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **Ausencia total de feedback en formularios**: En `register.php` no hay indicadores de campos obligatorios, sin validación en tiempo real, sin barra de progreso en formulario de 4 secciones
- **No hay confirmación de acciones completadas**: Después de registrarse, `userController.php` ejecuta INSERT pero no redirige ni muestra mensaje
- **Falta de indicadores visuales**: Botones sin estados (disabled/loading), sin spinners, sin breadcrumbs
- **Login sin implementación completa**: HTML existe pero backend no valida, usuario no sabe si login funcionó
- **Sin prevención de duplicados**: `createEvent.php` permite crear eventos iguales sin confirmación

## Impacto: **ALTO** ⚠️
Usuario no sabe si acciones se completaron, genera ansiedad y abandono.

## Recomendaciones:

### 1. Agregar mensajes de estado en backend:
```php
// En Controller/userController.php
if ($_POST['create']) {
    // Después de INSERT exitoso
    $_SESSION['success'] = "¡Registro exitoso! Por favor, inicia sesión.";
    header("Location: login.php");
    exit;
    
    // Después de validaciones fallidas
    $_SESSION['error'] = "El email ya está registrado.";
    header("Location: register.php");
    exit;
}
```

### 2. Mostrar mensajes en vistas:
```html
<!-- En register.php -->
<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger" role="alert">
    ✗ <?php echo htmlspecialchars($_SESSION['error']); 
         unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>
```

### 3. Indicador de progreso en formularios largos:
```html
<!-- En createEvent.php -->
<div class="progress-bar">
  <div class="step active" data-step="1">Información</div>
  <div class="step" data-step="2">Fecha/Hora</div>
  <div class="step" data-step="3">Ubicación</div>
  <div class="step" data-step="4">Contacto</div>
  <div class="step" data-step="5">Imágenes</div>
</div>
```

### 4. Botones con estado loading:
```html
<button type="submit" class="btn-submit" id="submitBtn">
  <span class="btn-text">Registrarse</span>
  <span class="btn-loading" style="display:none;">
    <i class="fas fa-spinner fa-spin"></i> Procesando...
  </span>
</button>

<script>
  document.querySelector('form').addEventListener('submit', function() {
    document.querySelector('.btn-text').style.display = 'none';
    document.querySelector('.btn-loading').style.display = 'inline';
    document.querySelector('#submitBtn').disabled = true;
  });
</script>
```

---

# 2️⃣ RELACIÓN ENTRE EL SISTEMA Y EL MUNDO REAL

**Estado actual:** ⚠️ **PARCIAL**

## Problemas detectados:

- **Lenguaje técnico en formularios**: Términos como "documentType", "postalCode" sin explicación clara
- **Falta de contexto en flujos**: `createEvent.php` no explica qué es categoría de evento ni diferencias presencial/virtual
- **Inconsistencia en idioma**: UI en español pero código backend en inglés
- **Iconos sin explicación**: Font Awesome usado pero sin contexto para usuarios nuevos
- **Formatos no claros**: `<input type="date">` sin indicar formato esperado (DD/MM/YYYY)

## Impacto: **MEDIO** ⚠️
Usuarios confundidos sobre qué hacer, especialmente nuevos usuarios.

## Recomendaciones:

### 1. Help text en formularios:
```html
<label for="documentType">
  Tipo de documento 
  <span class="help-icon" title="Pasaporte, DNI, Licencia, etc.">?</span>
</label>
<select id="documentType" name="documentType" required>
  <option value="">Selecciona un tipo...</option>
  <option value="DNI">DNI (Documento Nacional de Identidad)</option>
  <option value="PASAPORT">Pasaporte</option>
  <option value="LICENSE">Licencia de Conducir</option>
</select>
```

### 2. Explicar acciones:
```html
<!-- En forgotPassword.php -->
<p class="instruction-text">
  📧 Ingresa el email asociado a tu cuenta. Te enviaremos un enlace para 
  recuperar tu contraseña en los próximos 10 minutos.
</p>
```

### 3. Ejemplos visuales:
```html
<label for="eventDate">Fecha del evento (ej: 15 de Enero, 2025)</label>
<input type="date" id="eventDate" name="eventDate" required>
```

### 4. Tooltips para campos complejos:
```html
<label for="userType">
  Tipo de usuario
  <i class="fas fa-question-circle" 
     data-tooltip="Gestor: organiza eventos. Usuario: asiste a eventos.">
  </i>
</label>
```

---

# 3️⃣ CONTROL Y LIBERTAD DEL USUARIO

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **No hay botón "Cancelar"**: En `register.php`, `createEvent.php`, `profile.php` usuario está atrapado
- **Sin confirmación antes de acciones destructivas**: Eliminar cuenta sin diálogo de confirmación
- **Navegación limitada**: SIN breadcrumbs, usuario no sabe cómo volver
- **Sin logout visible**: No hay botón logout en navbar
- **Formularios no guardan**: Si recarga `createEvent.php`, pierde TODO
- **Sin búsqueda o filtrado**: `events.php` no tiene filtros funcionales

## Impacto: **ALTO** ⚠️
Usuario se siente atrapado, imposibilidad de corregir errores.

## Recomendaciones:

### 1. Botones cancelar/volver:
```html
<div class="form-actions">
  <button type="submit" class="btn-primary">Guardar</button>
  <a href="landingPage.php" class="btn-secondary">Cancelar</a>
</div>
```

### 2. Confirmación de acciones destructivas:
```html
<button type="button" class="btn-danger" onclick="confirmDelete()">
  Eliminar Cuenta
</button>

<script>
  function confirmDelete() {
    if (confirm('⚠️ Esta acción es irreversible. ¿Deseas eliminar tu cuenta?')) {
      document.getElementById('deleteForm').submit();
    }
  }
</script>
```

### 3. Navegación entre pasos:
```html
<div class="form-navigation">
  <button type="button" class="btn-prev" onclick="prevStep()" id="prevBtn">
    ← Atrás
  </button>
  <button type="button" class="btn-next" onclick="nextStep()">
    Siguiente →
  </button>
</div>
```

### 4. Auto-guardado:
```javascript
// En assets/js/autosave.js
const form = document.querySelector('form');
form.addEventListener('change', function() {
  const formData = new FormData(form);
  const data = Object.fromEntries(formData);
  localStorage.setItem('eventDraft', JSON.stringify(data));
});

// Restaurar al cargar
window.addEventListener('load', function() {
  const draft = localStorage.getItem('eventDraft');
  if (draft) {
    const data = JSON.parse(draft);
    Object.keys(data).forEach(key => {
      const field = document.querySelector(`[name="${key}"]`);
      if (field) field.value = data[key];
    });
  }
});
```

### 5. Logout en navbar:
```html
<?php if (isset($_SESSION['user_id'])): ?>
  <li><a href="Controller/userController.php?logout=true">Logout</a></li>
<?php else: ?>
  <li><a href="login.php">Login</a></li>
<?php endif; ?>
```

---

# 4️⃣ CONSISTENCIA Y ESTÁNDARES

**Estado actual:** ⚠️ **PARCIAL**

## Problemas detectados:

- **Botones sin estándares**: Colores inconsistentes entre páginas
- **Campos de formulario sin coherencia**: Padding/bordes diferentes en cada página
- **Tipografía sin escala**: 12px, 14px, 16px, 24px sin jerarquía
- **Colores de error/éxito**: Sin clases globales `.alert-success`, `.alert-danger`
- **Espaciado inconsistente**: `margin: 20px` vs `padding: 15px` sin sistema
- **Estados de input sin CSS**: Falta `:focus`, `:invalid`, `:disabled`, `:hover`

## Impacto: **MEDIO** ⚠️
Interfaz desorganizada, usuarios confundidos.

## Recomendaciones:

### 1. Sistema de componentes CSS:
```css
/* View/Assets/css/base/components.css */

.btn {
  display: inline-block;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #024ddf;
  color: white;
}
.btn-primary:hover {
  background-color: #0239b0;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(2, 77, 223, 0.3);
}
.btn-primary:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #f0f0f0;
  color: #024ddf;
  border: 2px solid #024ddf;
}

/* Campos de formulario */
.form-group {
  margin-bottom: 24px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #024ddf;
  box-shadow: 0 0 0 3px rgba(2, 77, 223, 0.1);
}

.form-group input:invalid {
  border-color: #e74c3c;
}

/* Alertas */
.alert {
  padding: 16px 20px;
  border-radius: 4px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-warning {
  background-color: #fff3cd;
  border: 1px solid #ffeaa7;
  color: #856404;
}
```

### 2. Escala de tipografía:
```css
/* View/Assets/css/base/typography.css */

h1 { font-size: 32px; font-weight: 700; line-height: 1.2; }
h2 { font-size: 28px; font-weight: 700; line-height: 1.2; }
h3 { font-size: 24px; font-weight: 600; line-height: 1.3; }
h4 { font-size: 20px; font-weight: 600; line-height: 1.3; }
h5 { font-size: 18px; font-weight: 600; line-height: 1.4; }
h6 { font-size: 16px; font-weight: 600; line-height: 1.4; }

body { font-size: 14px; line-height: 1.6; }
small { font-size: 12px; }
```

### 3. Sistema de espaciado:
```css
/* View/Assets/css/base/spacing.css */

:root {
  --spacing-xs: 4px;
  --spacing-sm: 8px;
  --spacing-md: 16px;
  --spacing-lg: 24px;
  --spacing-xl: 32px;
  --spacing-2xl: 48px;
}

.form-group {
  margin-bottom: var(--spacing-lg);
}
```

### 4. Main CSS ordenado:
```css
/* View/Assets/css/main.css */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600&display=swap');

@import 'base/style.css';
@import 'base/typography.css';
@import 'base/spacing.css';
@import 'base/components.css';
@import 'components/navbar.css';
@import 'components/footer.css';
```

---

# 5️⃣ PREVENCIÓN DE ERRORES

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **Sin validación en frontend**: No valida email format, contraseña fuerte, documento válido
- **Sin validación en backend**: `userController.php` no verifica si email existe, sin sanitización
- **Sin prevención de duplicados**: Usuario puede clickear 5 veces y crear 5 cuentas iguales
- **Fechas sin validación**: `createEvent.php` permite fechas pasadas
- **Sin límite de intentos**: Vulnerable a brute force en login
- **Sin confirmación de email**: No verifica que email sea válido

## Impacto: **ALTO** ⚠️
Datos inválidos en BD, brechas de seguridad.

## Recomendaciones:

### 1. Validación en frontend:
```html
<script>
  document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.querySelector('[name="password"]').value;
    const passwordRepeat = document.querySelector('[name="passwordRepeat"]').value;
    
    // Validar contraseña fuerte
    const passwordRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
    if (!passwordRegex.test(password)) {
      e.preventDefault();
      alert('❌ Contraseña debe tener:\n- 8+ caracteres\n- Mayúscula\n- Número\n- Símbolo');
      return;
    }
    
    if (password !== passwordRepeat) {
      e.preventDefault();
      alert('❌ Las contraseñas no coinciden');
      return;
    }
  });
</script>
```

### 2. Validación en backend:
```php
// En Controller/userController.php
private function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

private function emailExists($email, $conexion) {
    $stmt = $conexion->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

public function create() {
    if (isset($_POST['create'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!$this->validateEmail($email)) {
            $_SESSION['error'] = "Email inválido";
            header("Location: ../View/pages/register.php");
            exit;
        }
        
        if ($this->emailExists($email, $this->conexion)) {
            $_SESSION['error'] = "Email ya registrado";
            header("Location: ../View/pages/register.php");
            exit;
        }
    }
}
```

### 3. Prevenir duplicados con CSRF token:
```php
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Token inválido");
}

// Después de INSERT exitoso
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
```

---

# 6️⃣ RECONOCER ANTES QUE RECORDAR

**Estado actual:** ⚠️ **PARCIAL**

## Problemas detectados:

- **Sin navegación visual**: Navbar sin elemento activo resaltado
- **Sin breadcrumbs**: Usuario no sabe en qué sección está
- **Categorías sin descripción**: Solo nombre, sin ícono ni contexto
- **Sin autocomplete**: Campo de ciudad sin sugerencias
- **Errores sin contexto**: Sin indicador visual junto al campo (borde rojo)
- **Sin historial de búsquedas**: Usuario debe recordar búsquedas previas

## Impacto: **MEDIO** ⚠️
Usuarios olvidan dónde están y cómo volver.

## Recomendaciones:

### 1. Indicador en navbar:
```html
<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar">
  <ul>
    <li><a href="landingPage.php" 
           class="<?php echo $currentPage === 'landingPage.php' ? 'active' : ''; ?>">
      Inicio</a></li>
    <li><a href="events.php" 
           class="<?php echo $currentPage === 'events.php' ? 'active' : ''; ?>">
      Eventos</a></li>
  </ul>
</nav>

<style>
  .navbar a.active {
    color: #024ddf;
    font-weight: 600;
    border-bottom: 3px solid #024ddf;
  }
</style>
```

### 2. Breadcrumbs:
```html
<nav class="breadcrumb">
  <a href="landingPage.php">Inicio</a> / 
  <a href="events.php">Eventos</a> / 
  <span aria-current="page">Crear Evento</span>
</nav>
```

### 3. Autocomplete:
```html
<input type="text" id="city" name="city" list="cities" 
       placeholder="Ej: Madrid, Barcelona...">
<datalist id="cities">
  <option value="Madrid">
  <option value="Barcelona">
  <option value="Valencia">
</datalist>
```

### 4. Indicadores visuales de error:
```html
<div class="form-group has-error">
  <label for="email">Email</label>
  <input type="email" id="email" name="email" required>
  <span class="error-icon">✗ Email inválido</span>
</div>

<style>
  .form-group.has-error input {
    border-color: #e74c3c;
    background-color: #fef5f5;
  }
</style>
```

---

# 7️⃣ FLEXIBILIDAD Y EFICIENCIA DE USO

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **Sin búsqueda funcional**: `events.php` no tiene búsqueda implementada
- **Sin filtros avanzados**: No hay rango de fechas, precio, ubicación
- **Sin ordenamiento**: Eventos en mismo orden siempre
- **Sin guardar favoritos**: Usuario no puede marcar "interesado"
- **Sin compartir eventos**: No hay botón compartir en redes
- **Sin notificaciones**: Usuario no recibe alertas

## Impacto: **ALTO** ⚠️
Plataforma poco funcional, usuarios van a competencia.

## Recomendaciones:

### 1. Búsqueda funcional:
```html
<form method="GET" class="search-form">
  <input type="text" name="search" 
         placeholder="Busca eventos..." 
         value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
  <button type="submit"><i class="fas fa-search"></i></button>
</form>
```

### 2. Filtros avanzados:
```html
<div class="advanced-filters">
  <select name="dateRange">
    <option value="all">Cualquier fecha</option>
    <option value="this_week">Esta semana</option>
    <option value="next_2weeks">Próximas 2 semanas</option>
    <option value="this_month">Este mes</option>
  </select>
  
  <label><input type="checkbox" name="type" value="presencial"> Presencial</label>
  <label><input type="checkbox" name="type" value="virtual"> Virtual</label>
  
  <input type="range" name="price" min="0" max="500" step="10">
</div>
```

### 3. Ordenamiento:
```html
<select name="sort">
  <option value="relevancia">Relevancia</option>
  <option value="proximos">Más próximos</option>
  <option value="populares">Más populares</option>
  <option value="nuevos">Más nuevos</option>
</select>
```

### 4. Guardar favoritos:
```html
<button class="btn-favorite" data-event-id="123" onclick="toggleFavorite(this)">
  <i class="far fa-heart"></i> Guardar
</button>

<script>
  function toggleFavorite(btn) {
    const eventId = btn.dataset.eventId;
    fetch(`../Controller/eventController.php?toggleFavorite=${eventId}`, {
      method: 'POST'
    })
    .then(r => r.json())
    .then(data => {
      btn.classList.toggle('active');
      btn.innerHTML = data.isFavorite ? 
        '<i class="fas fa-heart"></i> Guardado' : 
        '<i class="far fa-heart"></i> Guardar';
    });
  }
</script>

<style>
  .btn-favorite.active i { color: #e74c3c; }
</style>
```

### 5. Compartir en redes:
```html
<div class="share-buttons">
  <button onclick="shareEvent('facebook')">
    <i class="fab fa-facebook"></i> Compartir
  </button>
  <button onclick="copyLink()">
    <i class="fas fa-link"></i> Copiar enlace
  </button>
</div>

<script>
  function shareEvent(platform) {
    const url = window.location.href;
    const title = document.querySelector('h1').textContent;
    const urls = {
      facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
      twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`
    };
    window.open(urls[platform], '_blank', 'width=600,height=400');
  }
  
  function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    alert('✓ Enlace copiado');
  }
</script>
```

---

# 8️⃣ DISEÑO ESTÉTICO Y MINIMALISTA

**Estado actual:** ✅ **CUMPLE BIEN**

## Fortalezas detectadas:

✅ Paleta coherente (Azul #024ddf, Dorado #FFD700)  
✅ Tipografía clara (Montserrat, Inter)  
✅ Diseño responsive  
✅ Componentes visuales coherentes

## Problemas detectados:

- **Formularios abrumadores**: 14 campos en una página
- **Espaciado inconsistente**: Sistema no estandarizado
- **Sin micro-interacciones**: Botones sin animaciones
- **Exceso de iconos sin jerarquía**

## Impacto: **BAJO** ⚠️

## Recomendaciones:

### 1. Dividir en pasos:
```html
<div class="form-wizard">
  <div class="wizard-step" data-step="1">
    <h2>Información Personal</h2>
    <!-- campos -->
  </div>
  <div class="wizard-step" data-step="2" style="display:none;">
    <h2>Contacto</h2>
    <!-- campos -->
  </div>
</div>
```

### 2. Micro-interacciones:
```css
.btn {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.event-card {
  animation: fadeInUp 0.5s ease-out;
}
```

---

# 9️⃣ RECONOCER, DIAGNOSTICAR Y CORREGIR ERRORES

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **Sin mensajes de error claros**: `userController.php` no maneja errores con mensajes
- **Errores genéricos**: HTML5 valida con mensajes por defecto
- **Sin explicación clara**: No dice si email o contraseña incorrectos
- **Sin ayuda para recuperarse**: No ofrece "¿Olvidaste contraseña?" en error
- **Errores de BD sin manejo**: Usuario ve error técnico
- **Sin estados visuales**: No hay borde rojo en campos con problemas

## Impacto: **ALTO** ⚠️

## Recomendaciones:

### 1. Sistema centralizado de errores:
```php
// En Config/ErrorHandler.php
class ErrorHandler {
    public static function handle($message, $type = 'error', $redirect = null) {
        $_SESSION[$type] = $message;
        if ($redirect) {
            header("Location: $redirect");
            exit;
        }
    }
    
    public static function render() {
        $html = '';
        
        if (isset($_SESSION['error'])) {
            $html .= '<div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Error:</strong> ' . htmlspecialchars($_SESSION['error']) . '
            </div>';
            unset($_SESSION['error']);
        }
        
        if (isset($_SESSION['success'])) {
            $html .= '<div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i>
                ' . htmlspecialchars($_SESSION['success']) . '
            </div>';
            unset($_SESSION['success']);
        }
        
        return $html;
    }
}
```

### 2. Mensajes específicos en login:
```php
// En Controller/userController.php
if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        ErrorHandler::handle('Por favor completa email y contraseña', 
                           'error', '../View/pages/login.php');
    }
    
    $stmt = $this->conexion->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        ErrorHandler::handle('No encontramos cuenta con ese email. 
                            <a href="register.php">¿Crear cuenta?</a>', 
                           'error', '../View/pages/login.php');
    }
    
    $row = $result->fetch_assoc();
    if (!password_verify($password, $row['password'])) {
        ErrorHandler::handle('Contraseña incorrecta. 
                            <a href="forgotPassword.php">¿Olvidaste tu contraseña?</a>', 
                           'error', '../View/pages/login.php');
    }
}
```

### 3. Validación con feedback real-time:
```html
<div class="form-group" id="emailGroup">
  <label for="email">Email *</label>
  <input type="email" id="email" name="email" required onblur="validateEmail(this)">
  <span class="field-error" id="emailError"></span>
</div>

<script>
  function validateEmail(field) {
    const email = field.value.trim();
    const errorSpan = document.querySelector('#emailError');
    const group = document.querySelector('#emailGroup');
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      group.classList.add('error');
      errorSpan.textContent = '✗ Email inválido';
      return false;
    }
    
    // Verificar si existe
    fetch(`../Controller/userController.php?checkEmail=${encodeURIComponent(email)}`)
      .then(r => r.json())
      .then(data => {
        if (data.exists) {
          group.classList.add('error');
          errorSpan.textContent = '✗ Email ya registrado';
        } else {
          group.classList.remove('error');
          errorSpan.textContent = '';
        }
      });
  }
</script>

<style>
  .form-group.error input {
    border-color: #e74c3c;
    background-color: #fef5f5;
  }
  .field-error {
    color: #e74c3c;
    font-size: 12px;
    display: block;
    margin-top: 4px;
  }
</style>
```

### 4. Mostrar mensajes en vistas:
```html
<div class="container">
    <?php require_once '../Config/ErrorHandler.php'; 
          echo ErrorHandler::render(); ?>
    <!-- resto del contenido -->
</div>
```

### 5. Requisitos de contraseña visibles:
```html
<div class="form-group">
  <label for="password">Contraseña *</label>
  <input type="password" id="password" name="password" required>
  
  <div class="password-requirements">
    <div class="req" id="req-length">✗ 8+ caracteres</div>
    <div class="req" id="req-upper">✗ Una mayúscula</div>
    <div class="req" id="req-number">✗ Un número</div>
    <div class="req" id="req-symbol">✗ Un símbolo (!@#$%^&*)</div>
  </div>
</div>

<script>
  document.querySelector('[name="password"]').addEventListener('input', function(e) {
    const pwd = e.target.value;
    
    document.querySelector('#req-length').classList.toggle('met', pwd.length >= 8);
    document.querySelector('#req-upper').classList.toggle('met', /[A-Z]/.test(pwd));
    document.querySelector('#req-number').classList.toggle('met', /[0-9]/.test(pwd));
    document.querySelector('#req-symbol').classList.toggle('met', /[!@#$%^&*]/.test(pwd));
  });
</script>

<style>
  .password-requirements { margin-top: 12px; font-size: 12px; }
  .req { color: #e74c3c; }
  .req.met { color: #27ae60; }
  .req.met::before { content: '✓ '; }
</style>
```

---

# 🔟 AYUDA Y DOCUMENTACIÓN

**Estado actual:** ❌ **NO CUMPLE**

## Problemas detectados:

- **Sin sección de Help o FAQ**: No existe página de ayuda
- **Sin documentación en formularios**: No hay explicaciones de qué hacer
- **Sin soporte visible**: No hay email, chat, o teléfono de soporte
- **Sin onboarding**: Usuario nuevo no sabe cómo empezar
- **Sin Términos/Privacidad**: No hay enlaces legales
- **Sin notificación de cambios**: No se comunican nuevas funcionalidades

## Impacto: **ALTO** ⚠️

## Recomendaciones:

### 1. Página de Help/FAQ:
```html
<!-- En View/pages/help.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Ayuda - Forever Events</title>
    <link rel="stylesheet" href="../Assets/css/main.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="help-container">
        <h1>Centro de Ayuda</h1>
        
        <div class="faq">
            <h2>Preguntas Frecuentes</h2>
            
            <details>
                <summary>¿Cómo creo una cuenta?</summary>
                <p>
                    1. Haz clic en "Registrarse"<br>
                    2. Completa tus datos<br>
                    3. Crea una contraseña fuerte<br>
                    4. ¡Listo!
                </p>
            </details>
            
            <details>
                <summary>¿Olvidé mi contraseña</summary>
                <p>
                    1. Ve a <a href="login.php">Login</a><br>
                    2. Haz clic en "¿Olvidaste tu contraseña?"<br>
                    3. Ingresa tu email<br>
                    4. Recibirás un enlace para resetearla
                </p>
            </details>
            
            <details>
                <summary>¿Cómo creo un evento?</summary>
                <p>
                    1. Inicia sesión<br>
                    2. Ve a "Crear Evento"<br>
                    3. Completa todos los campos<br>
                    4. Carga una imagen<br>
                    5. ¡Publicado!
                </p>
            </details>
        </div>
        
        <div class="contact-support">
            <h2>¿Aún necesitas ayuda?</h2>
            <ul>
                <li>📧 Email: soporte@foreverevents.com</li>
                <li>💬 Chat: Abre el chat en la esquina inferior</li>
                <li>📱 WhatsApp: +34 612 345 678</li>
            </ul>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
</body>
</html>
```

### 2. Tooltips contextuales:
```html
<div class="tooltip-trigger">
  <label for="userType">
    Tipo de Usuario
    <i class="fas fa-question-circle"></i>
  </label>
  <div class="tooltip-content">
    <strong>Gestor:</strong> Organiza eventos<br>
    <strong>Usuario:</strong> Participa en eventos
  </div>
</div>

<style>
  .tooltip-trigger .tooltip-content {
    visibility: hidden;
    background-color: #024ddf;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
  }
  
  .tooltip-trigger:hover .tooltip-content {
    visibility: visible;
    opacity: 1;
  }
</style>
```

### 3. Modal de onboarding:
```html
<div class="onboarding-modal" id="onboarding" style="display:none;">
  <div class="onboarding-content">
    <h2>¡Bienvenido a Forever Events! 👋</h2>
    
    <div class="onboarding-step" data-step="1">
      <h3>1. Descubre eventos</h3>
      <p>Explora miles de eventos en tu ciudad</p>
    </div>
    
    <div class="onboarding-step" data-step="2" style="display:none;">
      <h3>2. Crea tu evento</h3>
      <p>Organiza tu evento e invita a otros</p>
    </div>
    
    <div class="onboarding-step" data-step="3" style="display:none;">
      <h3>3. Conecta con otros</h3>
      <p>Encuentra personas con tus intereses</p>
    </div>
    
    <div class="onboarding-buttons">
      <button onclick="prevOnboarding()" class="btn btn-secondary">Atrás</button>
      <button onclick="nextOnboarding()" class="btn btn-primary">Siguiente</button>
    </div>
  </div>
</div>

<script>
  let onboardingStep = 1;
  
  function nextOnboarding() {
    if (onboardingStep < 3) {
      onboardingStep++;
    } else {
      localStorage.setItem('onboardingCompleted', 'true');
      document.querySelector('#onboarding').style.display = 'none';
    }
    updateOnboarding();
  }
  
  function updateOnboarding() {
    document.querySelectorAll('.onboarding-step').forEach(step => {
      step.style.display = step.dataset.step == onboardingStep ? 'block' : 'none';
    });
  }
  
  window.addEventListener('load', function() {
    if (!localStorage.getItem('onboardingCompleted')) {
      document.querySelector('#onboarding').style.display = 'flex';
    }
  });
</script>
```

### 4. Enlaces en navbar y footer:
```html
<!-- En View/components/navbar.php -->
<nav class="navbar">
  <ul>
    <li><a href="landingPage.php">Inicio</a></li>
    <li><a href="events.php">Eventos</a></li>
    <li><a href="help.php"><i class="fas fa-question-circle"></i> Ayuda</a></li>
    <li><a href="profile.php">Perfil</a></li>
  </ul>
</nav>

<!-- En View/components/footer.php -->
<footer>
  <ul>
    <li><a href="help.php">Ayuda</a></li>
    <li><a href="terms.php">Términos</a></li>
    <li><a href="privacy.php">Privacidad</a></li>
    <li><a href="contact.php">Contacto</a></li>
  </ul>
</footer>
```

---

# 📊 RESUMEN EJECUTIVO

| # | Heurística | Estado | Impacto | Prioridad |
|---|-----------|--------|--------|-----------|
| 1 | Visibilidad | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |
| 2 | Relación Sistema-Mundo | ⚠️ PARCIAL | MEDIO | 🟠 ALTA |
| 3 | Control y Libertad | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |
| 4 | Consistencia | ⚠️ PARCIAL | MEDIO | 🟠 ALTA |
| 5 | Prevención de Errores | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |
| 6 | Reconocer vs Recordar | ⚠️ PARCIAL | MEDIO | 🟠 ALTA |
| 7 | Flexibilidad | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |
| 8 | Diseño Estético | ✅ CUMPLE | BAJO | 🟢 MEDIA |
| 9 | Diagnóstico de Errores | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |
| 10 | Ayuda y Documentación | ❌ NO CUMPLE | ALTO | 🔴 CRÍTICA |

## 🎯 Acciones Inmediatas (CRÍTICAS):

1. ✅ Implementar retroalimentación de estado (mensajes)
2. ✅ Agregar validaciones backend robustas
3. ✅ Dar control al usuario (cancelar/volver)
4. ✅ Implementar búsqueda y filtros
5. ✅ Crear manejo de errores claro
6. ✅ Agregar ayuda y documentación

---

**Análisis completo basado en revisión exhaustiva del código, formularios y flujos de usuario.**
