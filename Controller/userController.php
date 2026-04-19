<?php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/User.php';

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

    header('Location: ../View/pages/login.php');
    exit();
}

header('Location: ../View/pages/login.php');
exit();

class userController
{
    private $conn;

    public function __construct()
    {
        $database = new Database('localhost', 'forever_events', 'root', '');
        $this->conn = $database->getConexion();
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

            $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->fetch_assoc()) {
                header('Location: ../View/pages/register.php?error=email-exists');
                exit();
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO usuarios 
                (nombre, apellido1, apellido2, fecha_nacimiento, tipo_usuario_id, email, password, telefono, tipo_documento_id, documento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userType = (int)$_POST['user_type'];
            $typeDoc = (int)$_POST['type_doc'];
            $surname = !empty($_POST['surname2']) ? $_POST['surname2'] : null;

            $stmt->bind_param(
                "ssssisssis",
                $_POST['name'],
                $_POST['surname1'],
                $surname,
                $_POST['date'],
                $userType,
                $_POST['email'],
                $passwordHash,
                $_POST['telephone'],
                $typeDoc,
                $_POST['document']
            );
            $stmt->execute();

            $stmt->close();
            $this->conn->close();

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

        $sql = "SELECT id, nombre, apellido1, email, password, tipo_usuario_id FROM usuarios WHERE email = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if ($fila && password_verify($password, $fila['password'])) {
            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['user_name'] = $fila['nombre'];
            $_SESSION['user_email'] = $fila['email'];
            $_SESSION['user_type'] = $fila['tipo_usuario_id'];

            $stmt->close();
            $this->conn->close();

            header('Location: ../View/pages/profile.php');
            exit();
        }

        $stmt->close();
        $this->conn->close();

        header('Location: ../View/pages/login.php?error=credenciales');
        exit();
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/pages/login.php');
            exit();
        }

        $id = $_SESSION['user_id'];
        $nombre = trim($_POST['name'] ?? '');
        $apellido1 = trim($_POST['surname1'] ?? '');
        $apellido2 = !empty($_POST['surname2']) ? trim($_POST['surname2']) : null;
        $telefono = trim($_POST['telephone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fecha = trim($_POST['date'] ?? '');

        if (empty($nombre) || empty($apellido1) || empty($email) || empty($telefono) || empty($fecha)) {
            header('Location: ../View/pages/profile.php?error=missing_data');
            exit();
        }

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare(
                "UPDATE usuarios SET nombre=?, apellido1=?, apellido2=?, telefono=?, email=?, fecha_nacimiento=?, password=? WHERE id=?"
            );
            $stmt->bind_param("ssssssi", $nombre, $apellido1, $apellido2, $telefono, $email, $fecha, $password, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE usuarios SET nombre=?, apellido1=?, apellido2=?, telefono=?, email=?, fecha_nacimiento=?  WHERE id=?"
            );
            $stmt->bind_param("ssssssi", $nombre, $apellido1, $apellido2, $telefono, $email, $fecha, $id);
        }

        $stmt->execute();
        $stmt->close();
        $this->conn->close();

        $_SESSION['user_name'] = $nombre;
        $_SESSION['user_email'] = $email;

        header('Location: ../View/pages/profile.php?success=updated');
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
