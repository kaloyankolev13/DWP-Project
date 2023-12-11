<?php
require_once 'controllers/Posts.php'; // Include the Posts class
require_once 'controllers/Comments.php';

$postsObj = new Posts();
$commentsObj = new Comments();


// Comments creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'], $_POST['comment'], $_POST['post_id'])) {
    $userId = $_SESSION['user_id']; // Assuming you store user id in session upon login
    $comment = $_POST['comment'];
    $postId = $_POST['post_id'];

    $commentResult = $commentsObj->addComment($userId, $postId, $comment);
}

// Post creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_post'])) {
    $userId = $_SESSION['user_id']; // Assuming you store user id in session upon login
    $caption = $_POST['caption'];
    $photo = $_FILES['photo']; // Assuming the name of your file input is 'photo'

    $createResult = $postsObj->createPost($userId, $caption, $photo);
    // Optionally, add a redirect or other response handling here
}

// Like action
if (isset($_POST['like'], $_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $postsObj->likePost($user_id, $post_id);
    // Optionally, add a redirect or other response handling here
}

// Fetch posts
$posts = $postsObj->fetchPosts();
?>
<div class="container">
    <div class="row">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="caption" placeholder="Enter caption" required>
            <input type="file" name="photo" required>
            <button type="submit" name="create_post">Create Post</button>
        </form>
        <?php foreach ($posts as $post) : ?>
            <?php var_dump($post); ?>
            <div class="col-12 mb-3">
                <div class="card">
                    <a href="/DWP_assignment/post-detail?post_id=<?= $post['post_id']; ?>">
                        <img src="<?= $post['photo_path'] ?>" class="card-img-top" alt="Post image">
                    </a>

                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/DWP_assignment/post-detail?post_id=<?= $post['post_id']; ?>"><?= $post['caption'] ?></a>
                            <br>
                            <a href="profile?user_id=<?= $post['user_id']; ?>"><?= $post['username'] ?></a>

                        </h5>
                        <p class="card-text"><small class="text-muted">Last updated <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                    </div>

                    <div class="card-footer">
                        <!-- Like Count -->
                        <p class="card-text"><?= $post['like_count'] ?> likes</p>
                        <!-- Like Button -->
                        <form action="" method="post" class="d-inline">
                            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                            <button type="submit" name="like" class="btn btn-primary">Like</button>
                        </form>
                        <?php
                        $postComments = $commentsObj->fetchComments($post['post_id']);
                        foreach ($postComments as $comment) : ?>
                            <div class="comment">
                                <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                                <?= htmlspecialchars($comment['content']) ?>
                            </div>
                        <?php endforeach; ?>
                        <!-- Comment Form -->
                        <form action="" method="post" class="d-inline">
                            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                            <input type="text" name="comment" class="form-control d-inline" placeholder="Write a comment...">
                            <button type="submit" name="submit_comment" class="btn btn-secondary">Comment</button>
                        </form>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>