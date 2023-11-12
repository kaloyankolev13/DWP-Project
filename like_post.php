<?php
session_start();
include 'connection.php';

if (isset($_POST['like'], $_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already liked the post
    $checkLike = $mysqli->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $checkLike->bind_param("ii", $user_id, $post_id);
    $checkLike->execute();
    $result = $checkLike->get_result();

    if ($result->num_rows === 0) {
        // Insert like
        $stmt = $mysqli->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    // You may want to handle unlike functionality too

    $checkLike->close();
}

$mysqli->close();
exit;