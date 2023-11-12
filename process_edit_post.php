<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'], $_POST['post_id'], $_POST['caption'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$caption = $_POST['caption'];

// Perform validation on the input data here

$stmt = $mysqli->prepare("UPDATE posts SET caption = ? WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("sii", $caption, $post_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Post updated successfully.";
} else {
    echo "Failed to update post or no changes made.";
}

$stmt->close();
$mysqli->close();

header('Location: profile.php'); // Redirect back to the profile or post list
exit;
