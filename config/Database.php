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

        // Lee primero las variables de Railway (MYSQL*), luego las del .env (DB_*) y si no existen usa los valores por defecto
        $this->host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? getenv('MYSQLHOST') ?: (getenv('DB_HOST') ?: '127.0.0.1');
        $this->username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USERNAME'] ?? getenv('MYSQLUSER') ?: (getenv('DB_USERNAME') ?: 'root');
        $this->password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? getenv('MYSQLPASSWORD') ?: (getenv('DB_PASSWORD') ?: '');
        $this->database = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_DATABASE'] ?? getenv('MYSQLDATABASE') ?: (getenv('DB_DATABASE') ?: 'techstore');
        $this->port = (int) ($_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? getenv('MYSQLPORT') ?: (getenv('DB_PORT') ?: 3306));
    }

    public function connect(): mysqli
    {
        if ($this->connection === null) {
            // Fuerza la conexión TCP/IP si por alguna razón el host quedó en 'localhost'
            $host = ($this->host === 'localhost') ? '127.0.0.1' : $this->host;

            $this->connection = new mysqli(
                $host,
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