<?php

class Database
{
    private string $host;
    private string $db_name;
    private string $usuario;
    private string $password;
    private ?mysqli $conn = null;

    public function __construct(string $host, string $db_name, string $usuario, string $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->usuario = $usuario;
        $this->password = $password;
    }

    public function getConexion(): mysqli
    {
        if ($this->conn === null) {
            $this->conn = new mysqli($this->host, $this->usuario, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                throw new RuntimeException("Error de conexión" . $this->conn->connect_error);
            }

            $this->conn->set_charset('utf8');
        }
        return $this->conn;
    }

    public function desconectar(): void
    {
        $this->conn?->close();
        $this->conn = null;
    }
}
