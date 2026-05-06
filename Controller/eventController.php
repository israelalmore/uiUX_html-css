<?php
session_start();

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/Event.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new eventController();

    if (isset($_POST['create'])) {
        $controller->create();
    }

    header('Location: ../View/pages/createEvent.php');
    exit();
}

header('Location: ../View/pages/createEvent.php');
exit();

class eventController
{
    private PDO $conn;

    public function __construct()
    {
        try {
            $database = new Database('localhost', 'forever_events', 'root', '');
            $this->conn = $database->getConexion();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            header('Location: ../View/pages/createEvent.php?error=db');
            exit();
        }
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/pages/login.php');
            exit();
        }

        if ((int) $_SESSION['user_type'] !== 1) {
            header('Location: ../View/pages/landingPage.php');
            exit();
        }

        if (
            !empty($_POST['title']) &&
            !empty($_POST['description']) &&
            !empty($_POST['category']) &&
            !empty($_POST['event_date']) &&
            !empty($_POST['event_time']) &&
            !empty($_POST['location']) &&
            !empty($_POST['postal_code']) &&
            !empty($_POST['city']) &&
            !empty($_POST['email'])
        ) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                header('Location: ../View/pages/createEvent.php?error=email-invalid');
                exit();
            }

            $fecha = $_POST['event_date'];
            $hora  = $_POST['event_time'];

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                header('Location: ../View/pages/createEvent.php?error=date-invalid');
                exit();
            }
            if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $hora)) {
                header('Location: ../View/pages/createEvent.php?error=time-invalid');
                exit();
            }

            try {
                $stmt = $this->conn->prepare(
                    "SELECT id FROM eventos WHERE titulo = :titulo AND fecha = :fecha"
                );
                $stmt->execute([
                    ':titulo' => $_POST['title'],
                    ':fecha'  => $fecha,
                ]);

                if ($stmt->fetch()) {
                    header('Location: ../View/pages/createEvent.php?error=event-exists');
                    exit();
                }

                $coverPath    = $this->uploadImage('cover_image', 'cover');
                $locationPath = $this->uploadImage('location_image', 'location');

                $stmt = $this->conn->prepare(
                    "INSERT INTO eventos
                    (titulo, descripcion, categoria, fecha, hora, direccion, codigo_postal, ciudad, email, imagen_portada, imagen_ubicacion, organizador_id)
                    VALUES (:titulo, :descripcion, :categoria, :fecha, :hora, :direccion, :codigo_postal, :ciudad, :email, :portada, :ubicacion, :organizador)"
                );

                $stmt->bindValue(':titulo', $_POST['title'], PDO::PARAM_STR);
                $stmt->bindValue(':descripcion', $_POST['description'], PDO::PARAM_STR);
                $stmt->bindValue(':categoria', $_POST['category'], PDO::PARAM_STR);
                $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindValue(':hora', $hora, PDO::PARAM_STR);
                $stmt->bindValue(':direccion', $_POST['location'], PDO::PARAM_STR);
                $stmt->bindValue(':codigo_postal', $_POST['postal_code'], PDO::PARAM_STR);
                $stmt->bindValue(':ciudad', $_POST['city'], PDO::PARAM_STR);
                $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $stmt->bindValue(':portada', $coverPath, $coverPath === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':ubicacion', $locationPath, $locationPath === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':organizador', (int) $_SESSION['user_id'], PDO::PARAM_INT);

                $stmt->execute();

                $newId = (int) $this->conn->lastInsertId();
            } catch (PDOException $e) {
                error_log('Error al crear evento: ' . $e->getMessage());
                header('Location: ../View/pages/createEvent.php?error=db');
                exit();
            }

            header('Location: ../View/pages/myEvents.php?success=created&id=' . $newId);
            exit();
        }

        header('Location: ../View/pages/createEvent.php?error=missing-data');
        exit();
    }

    private function uploadImage(string $field, string $prefix): ?string
    {
        if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES[$field]['type'], $allowedTypes, true)) {
            return null;
        }

        $uploadDir = __DIR__ . '/../View/Assets/img/events/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid('', true) . '.' . $ext;

        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $filename)) {
            return null;
        }

        return '../Assets/img/events/' . $filename;
    }

    private function delete()
    {
        // Implementar lógica de eliminación de evento
    }
}
