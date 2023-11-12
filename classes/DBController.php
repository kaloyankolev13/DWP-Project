<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBController {
    private static $host = 'localhost';
    private static $db = 'user_accounts';
    private static $user = 'root'; //TODO: change this
    private static $pass = '';
    private static $connection;

    private static function connect() {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli(self::$host, self::$user, self::$pass, self::$db);
            if (self::$connection->connect_error) {
                die('Connect Error (' . self::$connection->connect_errno . ') ' . self::$connection->connect_error);
            }
            self::$connection->set_charset("utf8");
        }
        return self::$connection;
    }

    public static function query($query, $params = []) {
        $stmt = self::connect()->prepare($query);
        if ($params) {
            $types = str_repeat("s", count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        if (explode(' ', $query)[0] == 'SELECT') {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return $stmt->affected_rows;
        }
    }

    public static function close() {
        if (isset(self::$connection)) {
            self::$connection->close();
        }
    }
}

// Usage
$result = DBController::query("SELECT * FROM some_table");
DBController::close();
