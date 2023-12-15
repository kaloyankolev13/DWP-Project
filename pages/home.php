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
            <!-- Post Creation Form -->
            <div class="col-12 mb-3">
                <form action="" method="post" enctype="multipart/form-data" class="mb-4">
                    <div class="form-group">
                        <input type="text" name="heading" class="form-control" placeholder="Enter heading" required>
                    </div>
                    <div class="form-group">
                        <textarea name="caption" class="form-control" placeholder="Enter caption" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="file" name="photo" class="form-control-file" required>
                    </div>
                    <button type="submit" name="create_post" class="btn btn-primary">Create Post</button>
                </form>
            </div>

            <!-- Posts Display -->
            <?php foreach ($posts as $post) : ?>
                <div class="col-6 mb-3">
                    <div class="card">
                        <a href="/DWP_assignment/post-detail?post_id=<?= $post['post_id']; ?>">
                            <img style="display: block; width: 100%; height: 200px; object-fit: cover; margin-left: auto; margin-right: auto;" src="<?= 'https://ethhos.store/' . $post['photo_path'] ?>" class="card-img-top" alt="Post image">
                        </a>

                        <div class="card-body">
                            <h5 class="card-title">
                                <h1><?= $post['heading']; ?></h1>
                                <p><?= $post['caption'] ?></p>
                                <br>
                                <a href="DWP_assignment/profile?user_id=<?= $post['user_id']; ?>">
                                    <?= $post['username']; ?>
                                </a>
                                <?php if(isset($_SESSION['user_id'])): ?>

                                   <?php
                            $isPostOwner = $_SESSION['user_id'] == $post['user_id'];
                            $isAdmin = $userObj->isAdmin($_SESSION['user_id']);
                            if ($isPostOwner || $isAdmin) : ?>
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                    <button type="submit" name="delete_post" class="btn btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php if(isset($_SESSION['user_id'])): ?>
                                <?php if ($_SESSION['user_id'] != $post['user_id']): ?>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="followed_id" value="<?= $post['user_id']; ?>">
                                        <?php if ($userObj->isFollowing($_SESSION['user_id'], $post['user_id'])): ?>
                                            <button type="submit" name="follow_unfollow" class="btn btn-secondary btn-sm">Unfollow</button>
                                        <?php else: ?>
                                            <button type="submit" name="follow_unfollow" class="btn btn-primary btn-sm">Follow</button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>

                            </h5>
                            <p class="card-text"><small class="text-muted">Last updated <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                        </div>

                        <div class="card-footer">
                            <p class="card-text"><?= $post['like_count'] ?> likes</p>
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
