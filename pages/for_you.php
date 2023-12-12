<?php
require_once 'controllers/User.php';

$userObj = new User();
$userId = $_SESSION['user_id']; // Make sure the user is logged in and you have their user ID
$followedPosts = $userObj->getPostsFromFollowedUsers($userId);
?>


<div class="container mt-4">
        <h1>For You</h1>
        <div class="row">
            <?php foreach ($followedPosts as $post) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?= htmlspecialchars($post['photo_path']) ?>" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post['caption']) ?></h5>
                            <p class="card-text">
                                Posted by: <a href="/user_profile?user_id=<?= $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a>
                            </p>
                            <p class="card-text"><small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>