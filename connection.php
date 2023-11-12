<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$host = 'localhost';
$db   = 'user_accounts';
$user = 'root'; //TODO change this
$pass = ''; 

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// $sqlFilePath = 'DB.sql';

// // Check if file exists
// if (file_exists($sqlFilePath)) {
//     $sqlCommands = file_get_contents($sqlFilePath);
    
//     if ($mysqli->multi_query($sqlCommands)) {
//         while ($mysqli->next_result()) {}  // Flush multi_queries
//     } else {
//         echo "Error executing SQL: " . $mysqli->error;
//     }
// } else {
//     echo "SQL file not found: $sqlFilePath";
// }

// $mysqli->close();

?>