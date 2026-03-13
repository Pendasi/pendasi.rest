<?php
namespace Pendasi\Rest\Core;

use PDO;

class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct($host, $db, $user, $pass) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function getInstance($host, $db, $user, $pass): Database {
        if (!self::$instance) {
            self::$instance = new Database($host, $db, $user, $pass);
        }
        return self::$instance;
    }

    public function connection(): PDO {
        return $this->pdo;
    }
}