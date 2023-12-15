<?php
require_once 'DBController.php';

class User
{
    private $dbController;

    public function isAdmin($userId) {
        // Example query, adjust according to your database structure
        $result = $this->dbController->query("SELECT * FROM super_admins WHERE user_id = ?", [$userId]);
        return !empty($result);
    }

    public function __construct()
    {
        $this->dbController = new DBController();
    }

    public function getUserDetails($user_id)
    {
        $query = "SELECT username, email, registration_date FROM users WHERE user_id = ?";
        return $this->dbController->query($query, [$user_id])[0] ?? null;
    }

    public function getUserPosts($user_id)
    {
        $postQuery = "SELECT p.post_id, p.caption, p.timestamp, ph.photo_path 
                      FROM posts p
                      LEFT JOIN photos ph ON p.post_id = ph.post_id
                      WHERE p.user_id = ? 
                      ORDER BY p.timestamp DESC";
        return $this->dbController->query($postQuery, [$user_id]);
    }
    public function followUser($follower_id, $followed_id)
    {
        $query = "INSERT INTO followers (follower_id, followed_id) VALUES (?, ?)";
        return $this->dbController->query($query, [$follower_id, $followed_id]);
    }

    public function unfollowUser($follower_id, $followed_id)
    {
        $query = "DELETE FROM followers WHERE follower_id = ? AND followed_id = ?";
        return $this->dbController->query($query, [$follower_id, $followed_id]);
    }

    public function getFollowing($user_id)
    {
        $query = "SELECT followed_id FROM followers WHERE follower_id = ?";
        return $this->dbController->query($query, [$user_id]);
    }

    public function getFollowers($user_id)
    {
        $query = "SELECT follower_id FROM followers WHERE followed_id = ?";
        return $this->dbController->query($query, [$user_id]);
    }

    public function isFollowing($follower_id, $followed_id)
    {
        $query = "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?";
        return count($this->dbController->query($query, [$follower_id, $followed_id])) > 0;
    }

    public function getPostsFromFollowedUsers($user_id) {
        $query = "SELECT p.post_id, p.caption, p.timestamp, ph.photo_path, u.username 
                  FROM posts p
                  LEFT JOIN photos ph ON p.post_id = ph.post_id
                  INNER JOIN followers f ON p.user_id = f.followed_id
                  INNER JOIN users u ON p.user_id = u.user_id
                  WHERE f.follower_id = ?
                  ORDER BY p.timestamp DESC";
        return $this->dbController->query($query, [$user_id]);
    }

    public function getFollowerCount($user_id) {
        $query = "SELECT COUNT(*) as follower_count FROM followers WHERE followed_id = ?";
        $result = $this->dbController->query($query, [$user_id]);
        return $result[0]['follower_count'] ?? 0;
    }

    public function getFollowerDetails($user_id) {
        $query = "SELECT u.user_id, u.username, u.email, u.registration_date
                  FROM users u
                  INNER JOIN followers f ON u.user_id = f.follower_id
                  WHERE f.followed_id = ?
                  ORDER BY u.username ASC";
        return $this->dbController->query($query, [$user_id]);
    }

    public function isUserBanned($userId) {
        $result = $this->dbController->query("SELECT * FROM banned_users WHERE user_id = ?", [$userId]);
        return !empty($result); // returns true if user is banned
    }
}
