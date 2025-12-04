<?php

namespace Debian\Php\auth\src\data;

use PDO;

class DBConnection
{
    private static ?DBConnection $instance = null;
    private $pdo;
    private string $dsn = 'auth.sqlite3';
    private function __construct()
    {
        $path = __DIR__ . "/../database/";

        $dir = dirname($path);
        if (!is_dir($path)) {
            mkdir($path,  0777, true);
        }

        try {
            $this->pdo = new PDO("sqlite:" . $path . $this->dsn);
        } catch (\PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public static function getInstance(): DBConnection
    {
        if (self::$instance === null) {
            self::$instance = new DBConnection();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
