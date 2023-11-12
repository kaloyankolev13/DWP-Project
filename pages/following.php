<h1>Posts from Accounts You Follow</h1>
<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($followedPosts as $post): // $followedPosts should be fetched from the database using the above SQL query ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= $post['photo_path'] ?>" class="card-img-top" alt="Post image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $post['caption'] ?></h5>
                        <p class="card-text">Posted by: <?= $post['username'] ?></p>
                        <p class="card-text"><small class="text-muted">Last updated <?= date("F j, Y, g:i a", strtotime($post['timestamp'])) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>