<?php
require_once 'DBController.php';

class Comments {
    private $dbController;

    public function __construct() {
        $this->dbController = new DBController();
    }

    public function fetchComments($postId) {
        $postId = filter_var($postId, FILTER_SANITIZE_NUMBER_INT);
        $comments = $this->dbController->query("SELECT c.comment_id, c.content, c.timestamp, c.user_id, u.username
                                                FROM comments c
                                                JOIN users u ON c.user_id = u.user_id
                                                WHERE c.post_id = ?
                                                ORDER BY c.timestamp ASC", [$postId]);
        return $comments;
    }

    public function addComment($userId, $postId, $content) {
        if (empty($content)) {
            throw new Exception("Comment cannot be empty.");
        }
        $this->dbController->query("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)", [$postId, $userId, $content]);
        return "Comment added successfully.";
    }


}
?>
