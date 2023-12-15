<?php
require_once 'controllers/Admin.php';
require_once 'controllers/User.php';

// Initialize User and Admin controller
$userModel = new User();
$adminController = new Admin();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    exit;
}

// Check if the logged-in user is an admin
if (!$userModel->isAdmin($_SESSION['user_id'])) {
    exit; 
}

$users = $adminController->getAllUsers();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'] ?? null;

    if (isset($_POST['ban'])) {
        $reason = $_POST['reason'] ?? 'No reason provided'; // Get ban reason or set default
        $adminController->banUser($_SESSION['user_id'], $userId, $reason);
        echo "<p>User ID: $userId has been banned.</p>";
    } elseif (isset($_POST['unban'])) {
        $adminController->unbanUser($userId);
        echo "<p>User ID: $userId has been unbanned.</p>";
    }

    // Refresh users list after ban/unban
    $users = $adminController->getAllUsers();
}
?>

<div class="container my-4">
    <h3>All Users</h3>
    <div class="row">
        <?php foreach ($users as $user) : ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                        <p>Member since: <?= date("F j, Y", strtotime($user['registration_date'])) ?></p>

                        <!-- Ban/Unban Button -->
                        <form action="" method="post">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <?php if ($user['ban_id'] > 0) : ?>
                                <button type="submit" name="unban" class="btn btn-success">Unban</button>
                            <?php else : ?>
                                <button type="submit" name="ban" class="btn btn-danger">Ban</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>