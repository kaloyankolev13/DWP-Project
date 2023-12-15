<?php
require_once 'controllers/Posts.php';

if (!isset($_SESSION['user_id'], $_GET['post_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_GET['post_id'];

$posts = new Posts();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['heading'], $_POST['caption'], $_POST['post_id'])) {
    $heading = $_POST['heading'];
    $caption = $_POST['caption'];

    try {
        $posts->editPost($post_id, $user_id, $heading, $caption);
        echo "Post updated successfully!";
        // Optionally, redirect or reload the page to show updated data
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$post = $posts->fetchPostById($post_id);

if (!$post || $post['user_id'] != $user_id) {
    echo "Post not found or you don't have permission to edit it.";
    exit;
}
?>

<div class="container">
    <h1>Edit Post</h1>
    <img src="<?="https://ethhos.store/".$post['photo_path']; ?>" class="card-img-top" alt="Post image">
    <form action="" method="post">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id); ?>">
        <div class="mb-3">
            <label for="heading" class="form-label">Heading</label>
            <input type="text" class="form-control" id="heading" name="heading" value="<?= htmlspecialchars($post['heading']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <textarea class="form-control" id="caption" name="caption"><?= htmlspecialchars($post['caption']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
