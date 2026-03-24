<?php

class Database {
    private $host = "localhost";
    private $db_name = "forever_events";
    private $usuario = "root";
    private $password = "";
    private $conexion;

    public function getConexion() {
        $this->conexion = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";

            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->conexion = new PDO($dsn, $this->usuario, $this->password, $opciones);

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return $this->conexion;
    }
}