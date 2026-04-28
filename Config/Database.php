<?php

class Database
{
    private string $host;
    private string $db_name;
    private string $usuario;
    private string $password;
    private ?PDO $conn = null;

    public function __construct(string $host, string $db_name, string $usuario, string $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->usuario = $usuario;
        $this->password = $password;
    }

    public function getConexion(): PDO
    {
        if ($this->conn === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->conn = new PDO($dsn, $this->usuario, $this->password, $options);
            } catch (PDOException $e) {
                throw new RuntimeException('Error de conexión: ' . $e->getMessage());
            }
        }
        return $this->conn;
    }

    public function desconectar(): void
    {
        $this->conn = null;
    }
}
