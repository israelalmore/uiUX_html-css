<?php
session_start();
require_once '../config/Database.php';
require_once '../model/User.php';

// if(issetPOST == create)
//     user->create();

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
            !empty($_POST['email']) &&
            !empty($_POST['pass']) &&
            !empty($_POST['pass2'])
        ) {

            if ($_POST['pass'] !== $_POST['pass2']) {
                die("Las contraseñas no coinciden");
            }

            // comprobar email duplicado
            $check = $this->conn->prepare("SELECT id FROM users WHERE email = :email");
            $check->execute([':email' => $_POST['email']]);

            if ($check->fetch()) {
                die("El email ya está registrado");
            }

            $sql = "INSERT INTO users 
            (name, surname1, surname2, birthdate, userType, email, password, telephone, language, documentType, document, city, postalCode, province) 
            VALUES 
            (:name, :surname1, :surname2, :birthdate, :userType, :email, :password, :telephone, :language, :documentType, :document, :city, :postalCode, :province)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':name' => $_POST['name'],
                ':surname1' => $_POST['surname1'],
                ':surname2' => $_POST['surname2'] ?? "",
                ':birthdate' => $_POST['date'],
                ':userType' => $_POST['userType'],
                ':email' => $_POST['email'],
                ':password' => password_hash($_POST['pass'], PASSWORD_DEFAULT),
                ':telephone' => $_POST['telephone'],
                ':language' => $_POST['language'],
                ':documentType' => $_POST['type_doc'],
                ':document' => $_POST['document'],
                ':city' => $_POST['city'],
                ':postalCode' => $_POST['postal_code'],
                ':province' => $_POST['province']
            ]);

            header('Location: login.php');
            exit();
        }
    }

    public function read()
    {
        return $_SESSION['users'] ?? [];
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
