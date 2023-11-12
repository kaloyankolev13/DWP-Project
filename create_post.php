<?php
include 'connection.php'; // Include the database connection
session_start();

// Assume $user_id holds the ID of the logged-in user
$user_id = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'], $_FILES['photo'])) {
    $caption = trim($_POST['caption']);
    if (empty($caption)) {
        die("Please enter a caption for the post.");
    }

    // Handle the file upload
    $photo = $_FILES['photo'];
    $upload_directory = 'uploads/'; // Ensure this directory exists and is writable
    $upload_path = $upload_directory . basename($photo['name']);

    // Check if the file is an actual image or fake image
    $check = getimagesize($photo["tmp_name"]);
    if($check === false) {
        die("File is not an image.");
    }

    // Attempt to upload the file
    if (!move_uploaded_file($photo['tmp_name'], $upload_path)) {
        die("There was an error uploading your file.");
    }

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        // Insert post data into 'posts' table
        if ($stmt = $mysqli->prepare("INSERT INTO posts (user_id, caption) VALUES (?, ?)")) {
            $stmt->bind_param("is", $user_id, $caption);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception('No rows affected. Unable to create post.');
            } else {
                $post_id = $stmt->insert_id;
                echo "Post created successfully with ID: " . $post_id;
            }
            $stmt->close();
        } else {
            throw new Exception("Error: " . $mysqli->error);
        }

        // Insert photo data into 'photos' table
        if ($stmt = $mysqli->prepare("INSERT INTO photos (post_id, photo_path) VALUES (?, ?)")) {
            $stmt->bind_param("is", $post_id, $upload_path);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception('No rows affected. Unable to upload photo.');
            }
            $stmt->close();
        } else {
            throw new Exception("Error: " . $mysqli->error);
        }

        // Commit the transaction
        $mysqli->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $mysqli->rollback();
        die($e->getMessage());
    }

    $mysqli->close();
}
?>
