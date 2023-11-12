<?php
include 'connection.php'; // Include the database connection

class Posts {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function createPost($userId, $caption, $photo) {
        if (empty($caption)) {
            throw new Exception("Please enter a caption for the post.");
        }

        $upload_directory = 'uploads/'; // Ensure this directory exists and is writable
        $upload_path = $upload_directory . basename($photo['name']);

        // Check if the file is an actual image
        $check = getimagesize($photo["tmp_name"]);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }

        // Attempt to upload the file
        if (!move_uploaded_file($photo['tmp_name'], $upload_path)) {
            throw new Exception("There was an error uploading your file.");
        }

        // Begin transaction
        $this->mysqli->begin_transaction();

        try {
            // Insert post data into 'posts' table
            if ($stmt = $this->mysqli->prepare("INSERT INTO posts (user_id, caption) VALUES (?, ?)")) {
                $stmt->bind_param("is", $userId, $caption);
                $stmt->execute();
                if ($stmt->affected_rows === 0) {
                    throw new Exception('No rows affected. Unable to create post.');
                }
                $post_id = $stmt->insert_id;
                $stmt->close();
            } else {
                throw new Exception("Error: " . $this->mysqli->error);
            }

            // Insert photo data into 'photos' table
            if ($stmt = $this->mysqli->prepare("INSERT INTO photos (post_id, photo_path) VALUES (?, ?)")) {
                $stmt->bind_param("is", $post_id, $upload_path);
                $stmt->execute();
                if ($stmt->affected_rows === 0) {
                    throw new Exception('No rows affected. Unable to upload photo.');
                }
                $stmt->close();
            } else {
                throw new Exception("Error: " . $this->mysqli->error);
            }

            // Commit the transaction
            $this->mysqli->commit();

            return "Post created successfully with ID: " . $post_id;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $this->mysqli->rollback();
            throw $e;
        }
    }
    public function fetchPosts() {
        $posts = []; // Initialize the array to hold the posts

        $query = "SELECT p.post_id, p.caption, p.timestamp, u.username, ph.photo_path, 
                    COUNT(l.like_id) as like_count
                    FROM posts p
                    LEFT JOIN users u ON p.user_id = u.user_id
                    LEFT JOIN photos ph ON p.post_id = ph.post_id
                    LEFT JOIN likes l ON p.post_id = l.post_id
                    GROUP BY p.post_id
                    ORDER BY p.timestamp DESC";

        if ($result = $this->mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                // Sanitize data with htmlspecialchars or a similar method
                $row['post_id'] = htmlspecialchars($row['post_id']);
                $row['caption'] = htmlspecialchars($row['caption']);
                $row['photo_path'] = htmlspecialchars($row['photo_path']) ?? 'path/to/default/image.jpg'; // Default image path if null
                $row['timestamp'] = htmlspecialchars($row['timestamp']);
                $row['username'] = htmlspecialchars($row['username']);
                
                // Append this row to the posts array
                $posts[] = $row;
            }
            $result->free();
        } else {
            // Handle error - perhaps set an error message or log it
        }

        return $posts;
    }
    public function fetchPostById($post_id) {
        // Sanitize the input to prevent SQL injection
        $post_id = filter_var($post_id, FILTER_SANITIZE_NUMBER_INT);

        // Prepare the SQL statement
        $stmt = $this->mysqli->prepare("SELECT p.post_id, p.caption, p.timestamp, u.username, ph.photo_path, COUNT(l.like_id) as like_count
                                        FROM posts p
                                        LEFT JOIN users u ON p.user_id = u.user_id
                                        LEFT JOIN photos ph ON p.post_id = ph.post_id
                                        LEFT JOIN likes l ON p.post_id = l.post_id
                                        WHERE p.post_id = ?
                                        GROUP BY p.post_id");

        // Bind parameters and execute
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        $stmt->close();

        if (!$post) {
            // Post not found, return false or handle the error as needed
            return false;
        }

        return $post;
    }
}

// Usage
try {
    $posts = new Posts($mysqli);
    $user_id = (int)$_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'], $_FILES['photo'])) {
        $caption = trim($_POST['caption']);
        $photo = $_FILES['photo'];

        echo $posts->createPost($user_id, $caption, $photo);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
