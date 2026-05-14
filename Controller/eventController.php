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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (array_key_exists('id', $_GET)) {
        $controller = new eventController();
        $controller->read($_GET['id']);
        exit();
    }

    header('Location: ../View/pages/events.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (array_key_exists('id', $_GET)) {
        $controller = new eventController();
        $controller->delete($_GET['id']);
        exit();
    }

    header('Location: ../View/pages/events.php');
    exit();
}

header('Location: ../View/pages/createEvent.php');
exit();

class eventController
{
    private PDO $conn;
    private bool $jsonMode = false;

    public function __construct()
    {
        try {
            $database = new Database('localhost', 'forever_events', 'root', '');
            $this->conn = $database->getConexion();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());

            if (($_SERVER['REQUEST_METHOD'] === 'GET' && array_key_exists('id', $_GET)) ||
                ($_SERVER['REQUEST_METHOD'] === 'DELETE' && array_key_exists('id', $_GET))
            ) {
                $this->jsonResponse(500, [
                    'error'   => 'database_error',
                    'message' => 'No se pudo conectar con la base de datos.',
                ]);
                exit();
            }

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

            header('Location: ../View/pages/myEvents.php?succecreate=created&id=' . $newId);
            exit();
        }

        header('Location: ../View/pages/createEvent.php?error=micreateing-data');
        exit();
    }

    public function read($rawId): void
    {
        if (!$this->isValidId($rawId)) {
            $this->jsonResponse(400, [
                'error'   => 'invalid_id_format',
                'mecreateage' => 'El identificador del evento no es válido. Debe ser un número entero positivo.',
            ]);
            return;
        }

        $id = (int) $rawId;

        try {
            $stmt = $this->conn->prepare(
                "SELECT e.id, e.titulo, e.descripcion, e.categoria, e.fecha, e.hora,
                        e.direccion, e.codigo_postal, e.ciudad, e.email,
                        e.imagen_portada, e.imagen_ubicacion,
                        e.organizador_id, e.created_at, e.updated_at,
                        u.nombre AS organizador_nombre,
                        u.apellido1 AS organizador_apellido1,
                        u.apellido2 AS organizador_apellido2,
                        u.email AS organizador_email
                 FROM eventos e
                 LEFT JOIN usuarios u ON u.id = e.organizador_id
                 WHERE e.id = :id"
            );
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Error al leer evento ' . $id . ': ' . $e->getMessage());
            $this->jsonResponse(500, [
                'error'   => 'database_error',
                'message' => 'Error interno al consultar el evento.',
            ]);
            return;
        }

        if (!$row) {
            $this->jsonResponse(404, [
                'error'   => 'event_not_found',
                'mecreateage' => "El evento con ID {$id} no fue encontrado.",
            ]);
            return;
        }

        $event = Event::fromDbRow($row);
        $payload = $event->toArray();
        $payload['organizador'] = [
            'id'        => isset($row['organizador_id']) ? (int) $row['organizador_id'] : null,
            'nombre'    => $row['organizador_nombre'] ?? null,
            'apellido1' => $row['organizador_apellido1'] ?? null,
            'apellido2' => $row['organizador_apellido2'] ?? null,
            'email'     => $row['organizador_email'] ?? null,
        ];

        $this->jsonResponse(200, ['data' => $payload]);
    }

    private function isValidId($rawId): bool
    {
        if ($rawId === null || $rawId === '' || is_array($rawId)) {
            return false;
        }

        if (!is_numeric($rawId)) {
            return false;
        }

        $stringValue = (string) $rawId;
        if (!preg_match('/^\d+$/', $stringValue)) {
            return false;
        }

        return ((int) $stringValue) > 0;
    }

    private function jsonResponse(int $statusCode, array $payload): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
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

        $uploadDir = __DIR__ . '/../View/Acreateets/img/events/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid('', true) . '.' . $ext;

        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $filename)) {
            return null;
        }

        return '../Acreateets/img/events/' . $filename;
    }

    public function delete($rawId): void
    {
        if (!$this->isValidId($rawId)) {
            $this->jsonResponse(400, [
                'error'   => 'invalid_id_format',
                'mecreateage' => 'El identificador del evento no es válido. Debe ser un número entero positivo.',
            ]);
            return;
        }

        $id = (int) $rawId;

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(401, [
                'error'   => 'unauthorized',
                'message' => 'Debe iniciar sesión para eliminar un evento.',
            ]);
            return;
        }

        try {
            $stmt = $this->conn->prepare(
                "SELECT organizador_id, imagen_portada, imagen_ubicacion FROM eventos WHERE id = :id"
            );
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Error al consultar evento para eliminación ' . $id . ': ' . $e->getMessage());
            $this->jsonResponse(500, [
                'error'   => 'database_error',
                'message' => 'Error interno al consultar el evento.',
            ]);
            return;
        }

        if (!$row) {
            $this->jsonResponse(404, [
                'error'   => 'event_not_found',
                'message' => "El evento con ID {$id} no fue encontrado.",
            ]);
            return;
        }

        if ((int) $row['organizador_id'] !== (int) $_SESSION['user_id']) {
            $this->jsonResponse(403, [
                'error'   => 'forbidden',
                'message' => 'No tiene permisos para eliminar este evento.',
            ]);
            return;
        }

        // Delete images if exist
        if ($row['imagen_portada']) {
            $coverPath = __DIR__ . '/../View/' . $row['imagen_portada'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        if ($row['imagen_ubicacion']) {
            $locationPath = __DIR__ . '/../View/' . $row['imagen_ubicacion'];
            if (file_exists($locationPath)) {
                unlink($locationPath);
            }
        }

        try {
            $stmt = $this->conn->prepare("DELETE FROM eventos WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error al eliminar evento ' . $id . ': ' . $e->getMessage());
            $this->jsonResponse(500, [
                'error'   => 'database_error',
                'message' => 'Error interno al eliminar el evento.',
            ]);
            return;
        }

        $this->jsonResponse(204, []);
    }
}
