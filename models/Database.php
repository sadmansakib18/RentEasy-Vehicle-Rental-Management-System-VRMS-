<?php
require_once __DIR__ . "/../config/database.php";

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (self::$connection->connect_error) {
                die("Database connection failed: " . self::$connection->connect_error);
            }
            self::$connection->set_charset("utf8mb4");
        }
        return self::$connection;
    }
}
?>
