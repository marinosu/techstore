<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private mysqli $db;
    
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function registrar(
        string $nombre,
        string $email,
        string $password
    ): bool {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios(nombre,email,password) VALUES (?,?,?)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error al preparar el registro: ' . $this->db->error);
        }

        $stmt->bind_param('sss', $nombre, $email, $passwordHash);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    public function obtenerPorEmail(
        string $email
    ): ?array {
        $sql = "SELECT * FROM usuarios WHERE email=? LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('s', $email);

        $stmt->execute();

        $resultado = $stmt->get_result();

        $usuario = $resultado->fetch_assoc();

        $stmt->close();

        return $usuario ?: null;
    }
}