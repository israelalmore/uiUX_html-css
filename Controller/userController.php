<?php
session_start();

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new userController();

    if (isset($_POST['create'])) {
        $controller->create();
    }

    if (isset($_POST['login'])) {
        $controller->read();
    }

    if (isset($_POST['delete'])) {
        $controller->delete();
    }
    if (isset($_POST['update'])) {
        $controller->update();
    }
    if (isset($_POST['deleteUser'])) {
        $controller->deleteUser();
    }

    header('Location: ../View/pages/login.php');
    exit();
}

header('Location: ../View/pages/login.php');
exit();

class userController
{
    private PDO $conn;

    public function __construct()
    {
        try {
            $database = new Database('localhost', 'forever_events', 'root', '');
            $this->conn = $database->getConexion();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            header('Location: ../View/pages/login.php?error=db');
            exit();
        }
    }

    public function create()
    {
        if (
            !empty($_POST['name']) &&
            !empty($_POST['surname1']) &&
            !empty($_POST['date']) &&
            !empty($_POST['user_type']) &&
            !empty($_POST['email']) &&
            !empty($_POST['telephone']) &&
            !empty($_POST['type_doc']) &&
            !empty($_POST['document']) &&
            !empty($_POST['password']) &&
            !empty($_POST['password2'])
        ) {
            if ($_POST['password'] !== $_POST['password2']) {
                header('Location: ../View/pages/register.php?error=passwords');
                exit();
            }

            try {
                $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = :email");
                $stmt->execute([':email' => $_POST['email']]);

                if ($stmt->fetch()) {
                    header('Location: ../View/pages/register.php?error=email-exists');
                    exit();
                }

                $stmt = $this->conn->prepare(
                    "INSERT INTO usuarios
                    (nombre, apellido1, apellido2, fecha_nacimiento, tipo_usuario_id, email, password, telefono, tipo_documento_id, documento)
                    VALUES (:nombre, :apellido1, :apellido2, :fecha, :tipo_usuario, :email, :password, :telefono, :tipo_doc, :documento)"
                );

                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $surname = !empty($_POST['surname2']) ? $_POST['surname2'] : null;

                $stmt->bindValue(':nombre', $_POST['name'], PDO::PARAM_STR);
                $stmt->bindValue(':apellido1', $_POST['surname1'], PDO::PARAM_STR);
                $stmt->bindValue(':apellido2', $surname, $surname === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':fecha', $_POST['date'], PDO::PARAM_STR);
                $stmt->bindValue(':tipo_usuario', (int) $_POST['user_type'], PDO::PARAM_INT);
                $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
                $stmt->bindValue(':telefono', $_POST['telephone'], PDO::PARAM_STR);
                $stmt->bindValue(':tipo_doc', (int) $_POST['type_doc'], PDO::PARAM_INT);
                $stmt->bindValue(':documento', $_POST['document'], PDO::PARAM_STR);

                $stmt->execute();
            } catch (PDOException $e) {
                error_log('Error en registro: ' . $e->getMessage());
                header('Location: ../View/pages/register.php?error=db');
                exit();
            }

            header('Location: ../View/pages/login.php');
            exit();
        }

        header('Location: ../View/pages/register.php?error=missing-data');
        exit();
    }

    public function read()
    {
        $email = trim($_POST['user'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            header('Location: ../View/pages/login.php?error=missing-data');
            exit();
        }

        try {
            $sql = "SELECT id, nombre, apellido1, email, password, tipo_usuario_id, avatar
                    FROM usuarios WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $fila = $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Error en login: ' . $e->getMessage());
            header('Location: ../View/pages/login.php?error=db');
            exit();
        }

        if ($fila && password_verify($password, $fila['password'])) {
            $_SESSION['user_id']     = $fila['id'];
            $_SESSION['user_name']   = $fila['nombre'];
            $_SESSION['user_email']  = $fila['email'];
            $_SESSION['user_type']   = $fila['tipo_usuario_id'];
            $_SESSION['user_avatar'] = $fila['avatar'];

            header('Location: ../View/pages/profile.php');
            exit();
        }

        header('Location: ../View/pages/login.php?error=credenciales');
        exit();
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/pages/login.php');
            exit();
        }

        $id        = (int) $_SESSION['user_id'];
        $nombre    = trim($_POST['name'] ?? '');
        $apellido1 = trim($_POST['surname1'] ?? '');
        $apellido2 = !empty($_POST['surname2']) ? trim($_POST['surname2']) : null;
        $telefono  = trim($_POST['telephone'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $fecha     = trim($_POST['date'] ?? '');

        if (empty($nombre) || empty($apellido1) || empty($email) || empty($telefono) || empty($fecha)) {
            header('Location: ../View/pages/profile.php?error=missing_data');
            exit();
        }

        // -- Contraseña nueva 
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password2'] ?? '';
        $passwordHash = null;

        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                header('Location: ../View/pages/profile.php?error=missing_data');
                exit();
            }


            $stmt = $this->conn->prepare("SELECT password FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();

            if (!password_verify($currentPassword, $row['password'])) {
                header('Location: ../View/pages/profile.php?error=wrong_password');
                exit();
            }
            if ($newPassword !== $confirmPassword) {
                header('Location: ../View/pages/profile.php?error=password_mismatch');
                exit();
            }

            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        }


        // Subir foto de perfil (solo gestores, tipo 1)
        $avatarPath = null;
        if ($_SESSION['user_type'] == 1 && !empty($_FILES['avatar']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = $_FILES['avatar']['type'];

            if (in_array($fileType, $allowedTypes)) {
                $uploadDir = __DIR__ . '/../View/Assets/img/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $ext      = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $id . '.' . $ext;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                    $avatarPath = '../Assets/img/avatars/' . $filename;
                    $_SESSION['user_avatar'] = $avatarPath;
                }
            }
        }

        // Update con o sin nueva contraseña y/o avatar 

        try {
            // Campos base siempre presentes
            $sets   = "nombre=:nombre, apellido1=:apellido1, apellido2=:apellido2,
                   telefono=:telefono, email=:email, fecha_nacimiento=:fecha";
            $params = [
                ':nombre'    => [$nombre,    PDO::PARAM_STR],
                ':apellido1' => [$apellido1, PDO::PARAM_STR],
                ':apellido2' => [$apellido2, $apellido2 === null ? PDO::PARAM_NULL : PDO::PARAM_STR],
                ':telefono'  => [$telefono,  PDO::PARAM_STR],
                ':email'     => [$email,     PDO::PARAM_STR],
                ':fecha'     => [$fecha,     PDO::PARAM_STR],
                ':id'        => [$id,        PDO::PARAM_INT],
            ];

            // Añadir contraseña solo si se validó
            if ($passwordHash !== null) {
                $sets .= ", password=:password";
                $params[':password'] = [$passwordHash, PDO::PARAM_STR];
            }

            // Añadir avatar solo si se subió
            if ($avatarPath !== null) {
                $sets .= ", avatar=:avatar";
                $params[':avatar'] = [$avatarPath, PDO::PARAM_STR];
            }

            $stmt = $this->conn->prepare("UPDATE usuarios SET $sets WHERE id=:id");

            foreach ($params as $key => [$value, $type]) {
                $stmt->bindValue($key, $value, $type);
            }

            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en update: ' . $e->getMessage());
            header('Location: ../View/pages/profile.php?error=db');
            exit();
        }

        $_SESSION['user_name']  = $nombre;
        $_SESSION['user_email'] = $email;

        header('Location: ../View/pages/profile.php?success=updated');
        exit();
    }

    public function deleteUser()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/pages/login.php');
            exit();
        }

        $id = (int) $_SESSION['user_id'];

        try {
            // Borrar imágenes de eventos del usuario
            $stmt = $this->conn->prepare("SELECT imagen_portada, imagen_ubicacion FROM eventos WHERE organizador_id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $eventos = $stmt->fetchAll();

            foreach ($eventos as $evento) {
                if ($evento['imagen_portada']) {
                    $path = __DIR__ . '/../View/' . $evento['imagen_portada'];
                    if (file_exists($path)) unlink($path);
                }
                if ($evento['imagen_ubicacion']) {
                    $path = __DIR__ . '/../View/' . $evento['imagen_ubicacion'];
                    if (file_exists($path)) unlink($path);
                }
            }

            // Borrar eventos y luego el usuario
            $stmt = $this->conn->prepare("DELETE FROM eventos WHERE organizador_id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteUser: ' . $e->getMessage());
            header('Location: ../View/pages/profile.php?error=db');
            exit();
        }

        session_unset();
        session_destroy();
        header('Location: ../View/pages/login.php?success=deleted');
        exit();
    }

    public function delete()
    {
        session_unset();
        session_destroy();
        header('Location: ../View/pages/login.php');
        exit();
    }
}
