<?php

namespace Core;


class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $dbname = $_ENV['DATABASE_NAME'];
            $user = $_ENV['DATABASE_USER'];
            $pass = $_ENV['DATABASE_PASSWORD'];



            try {
                self::$pdo = new \PDO(
                    "mysql:host=mariadb;dbname=$dbname",
                    $user,
                    $pass
                  );
            } catch (\PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
    private static function loadEnvironment(): void
    {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($value);
                }
            }
        }
    }
}