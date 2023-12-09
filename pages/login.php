<?php
require_once 'controllers/User.php'; // Adjust the path as necessary
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $userObj = new User();
        $loginResult = $userObj->login($email, $password);
        echo "<p>$loginResult</p>";
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Log In</h2>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary">Log In</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
        </div>
    </div>
</div>
