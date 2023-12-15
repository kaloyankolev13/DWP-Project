<?php
require_once 'DBController.php';

class Admin {
    private $dbController;

    public function __construct() {
        $this->dbController = new DBController();
    }

    public function banUser($adminId, $userId, $reason) {
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }
        if (empty($adminId)) {
            throw new Exception("Admin ID is required.");
        }
        if (empty($reason)) {
            throw new Exception("Ban reason is required.");
        }
        $this->dbController->query("INSERT INTO banned_users (user_id, super_admin_id, reason) VALUES (?, ?, ?)", [$userId, $adminId, $reason]);
        return "User ID: $userId has been banned by Admin ID: $adminId";
    }

    public function deleteUser($userId) {
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }
        $this->dbController->query("DELETE FROM users WHERE user_id = ?", [$userId]);
        return "User ID: $userId has been deleted";
    }

    public function deletePost($postId) {
        if (empty($postId)) {
            throw new Exception("Post ID is required.");
        }
        $this->dbController->query("DELETE FROM posts WHERE post_id = ?", [$postId]);
        return "Post ID: $postId has been deleted";
    }

    public function unbanUser($userId) {
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }
        $this->dbController->query("DELETE FROM banned_users WHERE user_id = ?", [$userId]);
        return "User ID: $userId has been unbanned";
    }

    public function getAllUsers() {
        $users = $this->dbController->query("SELECT u.user_id, u.username, u.email, u.registration_date, COALESCE(b.ban_id, 0) AS ban_id FROM users u LEFT JOIN banned_users b ON u.user_id = b.user_id");
        return $users;
    }
}
?>
