<?php
require_once 'controllers/Posts.php';
require_once 'controllers/Comments.php';
require_once 'controllers/User.php';


$postsObj = new Posts();
$commentsObj = new Comments();
$userObj = new User();

// Follow/Unfollow action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['follow_unfollow'], $_POST['followed_id'])) {
    $follower_id = $_SESSION['user_id'];
    $followed_id = $_POST['followed_id'];

    if ($userObj->isFollowing($follower_id, $followed_id)) {
        $userObj->unfollowUser($follower_id, $followed_id);
    } else {
        $userObj->followUser($follower_id, $followed_id);
    }
}

// Comments creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'], $_POST['comment'], $_POST['post_id'])) {
    $userId = $_SESSION['user_id'];
    $comment = $_POST['comment'];
    $postId = $_POST['post_id'];

    $commentResult = $commentsObj->addComment($userId, $postId, $comment);
}

// Post creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_post'])) {
    $userId = $_SESSION['user_id'];
    $heading = $_POST['heading'];
    $caption = $_POST['caption'];
    $photo = $_FILES['photo'];

    $createResult = $postsObj->createPost($userId, $heading, $caption, $photo);
}

// Delete post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_post'], $_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $deleteResult = $postsObj->deletePost($post_id, $user_id);
        echo "Post deleted successfully!";
        // Optionally, redirect to refresh the page
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Like action
if (isset($_POST['like'], $_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $postsObj->likePost($user_id, $post_id);
}

$posts = $postsObj->fetchPosts();
?>
<div class="container">
    <div class="row">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="heading" placeholder="Enter heading" required>
            <textarea type="text" name="caption" placeholder="Enter caption" required></textarea>
            <input type="file" name="photo" required>
            <button type="submit" name="create_post">Create Post</button>
        </form>
        <?php foreach ($posts as $post) : ?>
            <div class="col-12 mb-3">
                <div class="card">
                    <a href="/DWP_assignment/post-detail?post_id=<?= $post['post_id']; ?>">
                        <img src="<?= $post['photo_path'] ?>" class="card-img-top" alt="Post image">
                    </a>

                    <div class="card-body">
                        <h5 class="card-title">
                            <h1><?= $post['heading']; ?></h1>
                            <a href="/DWP_assignment/post-detail?post_id=<?= $post['post_id']; ?>"><?= $post['caption'] ?></a>
                            <br>
                            <a href="profile?user_id=<?= $post['user_id']; ?>">
                                <?= $post['username']; ?>
                            </a>
                            <?php if ($_SESSION['user_id'] == $post['user_id']) : ?>
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                    <button type="submit" name="delete_post" class="btn btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_id'] != $post['user_id']) : ?>
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="followed_id" value="<?= $post['user_id'] ?>">
                                    <?php if ($userObj->isFollowing($_SESSION['user_id'], $post['user_id'])) : ?>
                                        <button type="submit" name="follow_unfollow" class="btn btn-secondary btn-sm">Unfollow</button>
                                    <?php else : ?>
                                        <button type="submit" name="follow_unfollow" class="btn btn-primary btn-sm">Follow</button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>

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