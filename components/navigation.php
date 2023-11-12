<?php

$menuItems = [
    ['url' => '/', 'label' => 'Home'],
    ['url' => '/register', 'label' => 'Register'],
    ['url' => '/login', 'label' => 'Login'],
    ['url' => '/profile', 'label' => 'Profile'],
    ['url' => '/about', 'label' => 'About'],
];

function url($path) {
    $projectDir = '/DWP_assignment';
    return $projectDir . '/' . trim($path, '/');
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Your Space</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Always visible menu items -->
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo url('/'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/about'); ?>">About</a>
                </li>
                
                <!-- Conditional menu items -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/register'); ?>">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/login'); ?>">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/profile'); ?>">Profile</a>
                    </li>
                    <!-- Add a logout process link or form here -->
                <?php endif; ?>
            </ul>
            <!-- Search form removed -->
        </div>
    </div>
</nav>


