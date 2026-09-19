<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

class Database
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private int $port;

    private ?mysqli $connection = null;

    public function __construct()
    {
        /**
         * Si existe .env, lo cargamos.
         * En Railway las variables ya vienen
         * directamente del entorno.
         */
        $envFile = __DIR__ . '/../.env';

        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }

        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
        $this->username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';
        $this->database = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'techstore';
        $this->port = (int) ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 3306);
    }

    public function connect(): mysqli
    {
        if ($this->connection === null) {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database,
                $this->port
            );

            if ($this->connection->connect_error) {
                throw new Exception(
                    'Error de conexión a la base de datos: ' . $this->connection->connect_error
                );
            }

            $this->connection->set_charset('utf8mb4');
        }

        return $this->connection;
    }

    public function close(): void
    {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}

/** CONEXION A DATABASE */