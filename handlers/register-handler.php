<?php
require_once '../controllers/DBController.php';
require_once '../controllers/User.php'; // Assuming you have your User class in 'User.php'

try {
    session_start();
    $userObj = new User();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        echo $userObj->register($username, $email, $password);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>