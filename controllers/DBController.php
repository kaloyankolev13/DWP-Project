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

    public static function beginTransaction() {
        self::connect()->begin_transaction();
    }

    public static function commit() {
        self::connect()->commit();
    }

    public static function rollback() {
        self::connect()->rollback();
    }


    public static function query($query, $params = []) {
        $stmt = self::connect()->prepare($query);
        if ($params) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }
        if (strpos($query, 'SELECT') === 0) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } elseif (strpos($query, 'INSERT') === 0) {
            return $stmt->insert_id; // This returns the last inserted ID
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
