<?php
require_once 'DBController.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


class Auth {
        private $dbController;
        public function __construct() {
            $this->dbController = new DBController();
        }
        public function register($username, $email, $password) {
            if (empty($username)) {
                throw new Exception("Please enter a username.");
            }
            if (empty($email)) {
                throw new Exception("Please enter an email address.");
            }
            if (empty($password)) {
                throw new Exception("Please enter a password.");
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $this->dbController->beginTransaction();
            try {
                $user_id = $this->dbController->query("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", [$username, $email, $hashedPassword]);
                if ($user_id <= 0) {
                    throw new Exception("Failed to retrieve user ID after insertion.");
                }
                $this->dbController->commit();
                return "User created successfully with ID: " . $user_id;
            } catch (Exception $e) {
                $this->dbController->rollback();
                throw $e;
            }
        }
        public function login($email, $password) {
            if (empty($email)) {
                throw new Exception("Please enter an email address.");
            }
            if (empty($password)) {
                throw new Exception("Please enter a password.");
            }
            $result = $this->dbController->query("SELECT user_id, password FROM users WHERE email = ?", [$email]);
            $user = $result[0] ?? null;
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user['user_id'];
                    return "Logged in successfully!";
                } else {
                    throw new Exception("Incorrect password!");
                }
            } else {
                throw new Exception("User not found!");
            }
        }

        public function logout() {
            session_destroy();
            header('Location: /DWP_assignment/login');
        }
        public function isLoggedIn() {
            return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}


}


?>