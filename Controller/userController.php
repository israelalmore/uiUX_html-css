<?php
session_start();
require_once '../model/User.php';

class userController
{

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

            // Crear usuario
            $user = new User(
                $_POST['name'],
                $_POST['surname1'],
                $_POST['surname2'] ?? "",
                $_POST['date'],
                $_POST['userType'],
                $_POST['email'],
                $_POST['telephone'],
                $_POST['language'],
                $_POST['type_doc'],
                $_POST['document'],
                $_POST['city'],
                $_POST['postal_code'],
                $_POST['province'],
                password_hash($_POST['pass'], PASSWORD_DEFAULT)
            );


            $_SESSION['users'][] = [
                'name' => $user->name,
                'surname1' => $user->surname1,
                'surname2' => $user->surname2,
                'birthdate' => $user->birthdate,
                'userType' => $user->userType,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'language' => $user->language,
                'documentType' => $user->documentType,
                'document' => $user->document,
                'city' => $user->city,
                'postalCode' => $user->postalCode,
                'province' => $user->province
            ];
        }

        header('Location: login.php');
        exit();
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
