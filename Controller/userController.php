<?php
session_start();
require_once '../config/Database.php';
require_once '../model/User.php';
require_once 'userController.php'; // si se separa la clase en otro archivo
// MENÚ 
if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/pages/login.php');
    exit();
}

if (isset($_POST['create'])) {
    echo __LINE__;
    $controller = new userController();
    $controller->create();
    echo __LINE__;
}
if (isset($_POST['login'])) {
    echo __LINE__;
    $controller = new userController();
    $controller->read();
    echo __LINE__;
}
if (isset($_POST['delete'])) {
    echo __LINE__;
    $controller = new userController();
    $controller->delete();
    echo __LINE__;
}
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
        echo __LINE__;
        if (
            !empty($_POST['name']) &&
            !empty($_POST['surname1']) &&
            !empty($_POST['email']) &&
            !empty($_POST['password']) &&
            !empty($_POST['password2'])
        ) {

            if ($_POST['password'] !== $_POST['password2']) {
                die("Las contraseñas no coinciden");
            }

            // comprobar email duplicado
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->fetch_assoc()) {
                die("El email ya está registrado");
            }

            // INSERT con mysqli
            $stmt = $this->conn->prepare("INSERT INTO users 
            (name, surname1, surname2, birthdate, userType, email, password, telephone, language, documentType, document, city, postalCode, province) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt->bind_param(
                "ssssssssssssss",
                $_POST['name'],
                $_POST['surname1'],
                $_POST['surname2'],
                $_POST['date'],
                $_POST['user_type'],
                $_POST['email'],
                $passwordHash,
                $_POST['telephone'],
                $_POST['language'],
                $_POST['type_doc'],
                $_POST['document'],
                $_POST['city'],
                $_POST['postal_code'],
                $_POST['province']
            );

            $stmt->execute();

            header('Location: login.php');
            exit();
        } else {
            die("Faltan datos");
        }
    }

    public function read()
    {
        $email = $_POST['user'];
        $password = $_POST['password'];
        // return $_SESSION['users'] ?? [];
        echo "en read";
        $sql = "SELECT * FROM usuarios WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ss", $email, $password);  // i=integer, s=string

        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Recorrer resultados
            while ($fila = $resultado->fetch_assoc()) {
                echo "Nombre: " . $fila['nombre'] . " - ";
                echo "Email: " . $fila['email'] . "<br>";
            }

            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['user_name'] = $fila['nombre'];
            $_SESSION['user_email'] = $fila['email'];

            header('Location: ../View/pages/profile.php');
            exit();
        } else {
            //falta handler error al fallar login
            header('Location: ../View/pages/login.php');
            echo "No se encontraron resultados";
        }

        $stmt->close();
        $this->conn->close();
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
