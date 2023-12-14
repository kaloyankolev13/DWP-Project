
<div class="container mt-4">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <img src="<?= htmlspecialchars($post['photo_path']); ?>" class="card-img-top" alt="Post image">
                <div class="card-body">
                    <?=var_dump($post);?>
                    <h1><?= $post['heading'] ?></h1>
                    <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
                    <p class="card-text">Posted by: <?= htmlspecialchars($post['username']); ?></p>
                    <p class="card-text"><?= $post['like_count']; ?> likes</p>
                    <p class="card-text"><small class="text-muted">Last updated <?= date("F j, Y, g:i a", strtotime($post['timestamp'])); ?></small></p>
                </div>
            </div>
        </div>
    </div>
</div>

