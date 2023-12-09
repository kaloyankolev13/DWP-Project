
<?php
require_once 'DBController.php';
class Posts {

    private $dbController;

    public function __construct() {
        $this->dbController = new DBController();
    }

    public function createPost($userId, $caption, $photo) {
        if (empty($caption)) {
            throw new Exception("Please enter a caption for the post.");
        }
        $upload_directory = 'uploads/'; 
        $upload_path = $upload_directory . basename($photo['name']);
        $check = getimagesize($photo["tmp_name"]);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }
        if (!move_uploaded_file($photo['tmp_name'], $upload_path)) {
            throw new Exception("There was an error uploading your file.");
        }
        $this->dbController->beginTransaction();
    try {
        // Insert post data into 'posts' table and get the last inserted ID
        $post_id = $this->dbController->query("INSERT INTO posts (user_id, caption) VALUES (?, ?)", [$userId, $caption]);

        if ($post_id <= 0) {
            throw new Exception("Failed to retrieve post ID after insertion.");
        }

        // Insert photo data into 'photos' table with the correct post_id
        $this->dbController->query("INSERT INTO photos (post_id, photo_path) VALUES (?, ?)", [$post_id, $upload_path]);

        $this->dbController->commit();
        return "Post created successfully with ID: " . $post_id;
    } catch (Exception $e) {
        $this->dbController->rollback();
        throw $e;
    }
    }

    public function fetchPosts() {
        $posts = DBController::query("SELECT p.post_id, p.caption, p.timestamp, p.user_id, u.username, ph.photo_path, 
                                      COUNT(l.like_id) as like_count
                                      FROM posts p
                                      LEFT JOIN users u ON p.user_id = u.user_id
                                      LEFT JOIN photos ph ON p.post_id = ph.post_id
                                      LEFT JOIN likes l ON p.post_id = l.post_id
                                      GROUP BY p.post_id
                                      ORDER BY p.timestamp DESC");
        return $posts;
    }


    public function fetchPostById($post_id) {
        $post_id = filter_var($post_id, FILTER_SANITIZE_NUMBER_INT);

        $post = DBController::query("SELECT p.post_id, p.caption, p.timestamp, u.username, ph.photo_path, COUNT(l.like_id) as like_count
                                     FROM posts p
                                     LEFT JOIN users u ON p.user_id = u.user_id
                                     LEFT JOIN photos ph ON p.post_id = ph.post_id
                                     LEFT JOIN likes l ON p.post_id = l.post_id
                                     WHERE p.post_id = ?
                                     GROUP BY p.post_id", [$post_id]);

        return $post ? $post[0] : false;
    }
    public function likePost($userId, $postId) {
        $this->dbController->beginTransaction();
        try {
            $checkLike = $this->dbController->query("SELECT * FROM likes WHERE user_id = ? AND post_id = ?", [$userId, $postId]);
            if (count($checkLike) === 0) {
                $this->dbController->query("INSERT INTO likes (post_id, user_id) VALUES (?, ?)", [$postId, $userId]);
            } else {
                $this->dbController->query("DELETE FROM likes WHERE user_id = ? AND post_id = ?", [$userId, $postId]);
            }
            $this->dbController->commit();
        } catch (Exception $e) {
            $this->dbController->rollback();
            throw $e;
        }
    }
}

// Usage
try {
    $user_id = (int)$_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'], $_FILES['photo'])) {
        $caption = trim($_POST['caption']);
        $photo = $_FILES['photo'];

        $posts = new Posts();
        echo $posts->createPost($user_id, $caption, $photo);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
