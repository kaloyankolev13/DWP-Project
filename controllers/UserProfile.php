<?php
require_once 'DBController.php';

class UserProfile {
    private $dbController;

    public function __construct() {
        $this->dbController = new DBController();
    }

    public function getUserDetails($user_id) {
        $query = "SELECT username, email, registration_date FROM users WHERE user_id = ?";
        return $this->dbController->query($query, [$user_id])[0] ?? null;

    }

    public function getUserPosts($user_id) {
        $postQuery = "SELECT p.post_id, p.caption, p.timestamp, ph.photo_path 
                      FROM posts p
                      LEFT JOIN photos ph ON p.post_id = ph.post_id
                      WHERE p.user_id = ? 
                      ORDER BY p.timestamp DESC";
        return $this->dbController->query($postQuery, [$user_id]);
    }
}