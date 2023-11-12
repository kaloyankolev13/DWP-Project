<!-- Form for registering -->
    <?php
        include(dirname(__FILE__) . '/../registration.php');
    ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Sign Up</h2>
                    <form action="registration.php" method="post" class="row g-3">
                        <div class="col-12">
                            <label for="username" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your full name" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Log in</a>.</p>
            </div>
        </div>
    </div>
</div>