<?php

namespace App;

use Exception;

use PDO;
use RuntimeException;

final class Connection
{
    private static ?Connection $conn = null;

    public static function get(): ?Connection
    {
        if (null === Connection::$conn)
            Connection::$conn = new self();
        return Connection::$conn;
    }

    public function connect(): PDO
    {
        try {
            $configFile = dirname(__DIR__) . '/config/db.php';
            if (!file_exists($configFile))
                throw new RuntimeException("Config file not found: $configFile");

            $config = require_once $configFile;
            $conStr = sprintf(
                "mysql:host=%s;dbname=%s;user=%s;password=%s",
                $config['host'],
                $config['database'],
                $config['user'],
                $config['password']
            );

            $pdo = new PDO($conStr);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES " . $config['charset']);
        } catch (Exception $e) {
            Logger::log($e->getMessage(), 'app.log');
            die("Error!: " . $e->getMessage() . PHP_EOL);
        }

        return $pdo;
    }

    protected function __construct() {}
}
