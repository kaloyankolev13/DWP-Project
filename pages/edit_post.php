<?php
include 'connection.php';

if (!isset($_SESSION['user_id'], $_GET['post_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_GET['post_id'];

$stmt = $mysqli->prepare("SELECT * FROM posts WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$post) {
    echo "Post not found or you don't have permission to edit it.";
    exit;
}
?>



<div class="container">
    <h1>Edit Post</h1>
    <form action="process_edit_post.php" method="post">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id); ?>">
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <textarea class="form-control" id="caption" name="caption"><?= htmlspecialchars($post['caption']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

