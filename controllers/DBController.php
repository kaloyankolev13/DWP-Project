<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBController {
    private static $host='ethhos.store.mysql';
    private static $db = 'ethhos_storeuser_accounts';
    private static $user = 'ethhos_storeuser_accounts';
    private static $pass = 'vajnaparola';
    private static $connection;

    // Method to load environment variables from .env file
    private static function loadEnvironmentVariables($envFilePath) {
        if (!file_exists($envFilePath)) {
            return;
        }

        $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

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
