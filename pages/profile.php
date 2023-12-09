<?php
require_once 'controllers/UserProfile.php'; // Adjust the path as necessary

$userProfile = new UserProfile();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $userProfile->getUserDetails($user_id);
if (!$user) {
    die("User not found.");
}

$posts = $userProfile->getUserPosts($user_id);

// Sanitize the user data
foreach ($user as $key => $value) {
    $user[$key] = htmlspecialchars($value);
}

// Sanitize the posts data
foreach ($posts as $index => $post) {
    foreach ($post as $key => $value) {
        $posts[$index][$key] = htmlspecialchars($value);
    }
}
?>



<div class="container my-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="Profile Image">
                <div class="card-body">
                    <h4 class="card-title"><?= $user['username'] ?></h4>
                    <p class="card-text">Member since <?= date("F j, Y", strtotime($user['registration_date'])) ?></p>
                    <a href="#" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile Details</h5>
                    <form action="process_profile_update.php" method="post">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="userName" value="<?= $user['username'] ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="userEmail" value="<?= $user['email'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container my-4">
    <h3>My Posts</h3>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <a href="/DWP_assignment/edit_post?post_id=<?= $post['post_id'] ?>" class="card-link">
                    <div class="card">
                        <img src="<?= $post['photo_path'] ?: 'https://via.placeholder.com/150' ?>" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= $post['caption'] ?></h5>
                            <p class="card-text"><small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

