<?php

include 'controllers/posts.php';
include 'connection.php';

$postsObj = new Posts($mysqli);

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $post = $postsObj->fetchPostById($post_id);

    if (!$post) {
        header("HTTP/1.0 404 Not Found");
        echo "Post not found.";
        exit;
    }} else {
        header("Location: index.php");
        exit;
    }
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <img src="<?= htmlspecialchars($post['photo_path']); ?>" class="card-img-top" alt="Post image">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
                    <p class="card-text">Posted by: <?= htmlspecialchars($post['username']); ?></p>
                    <p class="card-text"><?= $post['like_count']; ?> likes</p>
                    <p class="card-text"><small class="text-muted">Last updated <?= date("F j, Y, g:i a", strtotime($post['timestamp'])); ?></small></p>
                </div>
            </div>
        </div>
    </div>
</div>

