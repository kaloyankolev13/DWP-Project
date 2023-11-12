<?php
include 'connection.php'; // Your database connection file

// Check if the user is logged in, assuming you've set $_SESSION['user_id'] when they logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query = "SELECT username, email, registration_date FROM users WHERE user_id = ?";
$stmt = $mysqli->prepare($query);

// Check if the prepare was successful
if ($stmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user data
if ($user = $result->fetch_assoc()) {
    // Sanitize the output
    foreach ($user as $key => $value) {
        $user[$key] = htmlspecialchars($value);
    }
} else {
    // Handle the case where the user doesn't exist in the database
    die("User not found.");
}

$stmt->close();

$postQuery = "SELECT p.post_id, p.caption, p.timestamp, ph.photo_path 
              FROM posts p
              LEFT JOIN photos ph ON p.post_id = ph.post_id
              WHERE p.user_id = ? 
              ORDER BY p.timestamp DESC";
$postStmt = $mysqli->prepare($postQuery);

if ($postStmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

$postStmt->bind_param("i", $user_id);
$postStmt->execute();
$postsResult = $postStmt->get_result();

$posts = [];
while ($postRow = $postsResult->fetch_assoc()) {
    $posts[] = [
        'post_id' => htmlspecialchars($postRow['post_id']),
        'caption' => htmlspecialchars($postRow['caption']),
        'timestamp' => htmlspecialchars($postRow['timestamp']),
        'photo_path' => htmlspecialchars($postRow['photo_path']),
    ];
}

$postStmt->close();
$mysqli->close();
?>



<div class="container my-4">
    
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <!-- Replace with the actual path to the user's profile picture or a placeholder if not available -->
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="Profile Image">
                <div class="card-body">
                    <h4 class="card-title"><?= $user['username'] ?></h4>
                    <!-- You can add more user details here if needed -->
                    <p class="card-text">Member since <?= date("F j, Y", strtotime($user['registration_date'])) ?></p>
                    <a href="#" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile Details</h5>
                    <!-- The form action should point to a script that processes the form data -->
                    <form action="process_profile_update.php" method="post">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="userName" value="<?= $user['username'] ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="userEmail" value="<?= $user['email'] ?>">
                        </div>
                        <!-- Include additional fields as necessary -->
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
                <!-- The entire card is clickable and links to the edit post page -->
                <a href="/DWP_assignment/edit_post?post_id=<?= $post['post_id'] ?>" class="card-link">
                    <div class="card">
                        <img src="<?= $post['photo_path'] ?: 'https://via.placeholder.com/150' ?>" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= $post['caption'] ?></h5>
                            <p class="card-text"><small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                            <!-- Add an edit button or icon here if preferred -->
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>