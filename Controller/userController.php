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
            !empty($_POST['surname2']) &&
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
            $stmt->bind_param(
                "ssssssssss",
                $_POST['name'],
                $_POST['surname1'],
                $_POST['surname2'],
                $_POST['date'],
                $_POST['user_type'],
                $_POST['email'],
                $passwordHash,
                $_POST['telephone'],
                $_POST['type_doc'],
                $_POST['document']
            );
            $stmt->execute();

            header('Location: ../View/pages/login.php');
            exit();
        }

        header('Location: ../View/pages/register.php?error=missing_data');
        exit();
    }

    public function read()
    {
        $email = trim($_POST['user'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            header('Location: ../View/pages/login.php?error=missing_data');
            exit();
        }

        $sql = "SELECT id, nombre, apellido1, email, password FROM usuarios WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if ($fila) {
            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['user_name'] = $fila['nombre'];
            $_SESSION['user_email'] = $fila['email'];

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
        // pendiente
    }

    public function delete()
    {
        // pendiente
    }
}
