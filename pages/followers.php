<?php
    require_once 'controllers/User.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $userObj = new User();
    $user_id = $_SESSION['user_id']; //TODO: Need to make it so that it gets for every user
    $followers = $userObj->getFollowerDetails($user_id);
?>

<div class="container my-4">
    <h2>Followers</h2>
    <div class="row">
        <?php foreach ($followers as $follower): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Profile Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($follower['username']) ?></h5>
                        <p class="card-text">Email: <?= htmlspecialchars($follower['email']) ?></p>
                        <p class="card-text">Member since: <?= date("F j, Y", strtotime($follower['registration_date'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>