<?php
require_once __DIR__ . '/../config/Database.php';

class Articulo
{
    private mysqli $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * READ - Obtener todos
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT * FROM articulos ORDER BY id DESC";

        $resultado = $this->db->query($sql);

        if (!$resultado) {
            throw new Exception('Error al consultar los artículos.');
        }

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * READ - Obtener uno
     */
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM articulos WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $articulo = $resultado->fetch_assoc();

        $stmt->close();

        return $articulo ?: null;
    }

    /**
     * CREATE
     */
    public function crear(
        string $nombre,
        string $descripcion,
        string $categoria,
        float $precio,
        int $stock,
        string $marca
    ): bool {
        $sql = "INSERT INTO articulos(nombre,descripcion,categoria,precio,stock,marca) VALUES (?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('sssdis',$nombre,$descripcion,$categoria,$precio,$stock,$marca);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    /**
     * UPDATE
     */
    public function actualizar(
        int $id,
        string $nombre,
        string $descripcion,
        string $categoria,
        float $precio,
        int $stock,
        string $marca
    ): bool {
        $sql = "UPDATE articulos SET nombre=?,descripcion=?,categoria=?,precio=?,stock=?,marca=? WHERE id=?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('sssdisi',$nombre,$descripcion,$categoria,$precio,$stock,$marca,$id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    /**
     * DELETE
     */
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM articulos WHERE id=?";
        
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('i', $id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
}