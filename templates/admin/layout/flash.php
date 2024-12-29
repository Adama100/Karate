<div class="container mt-2">
    <?php if(isset($_SESSION['flash'])): ?>
        <?php foreach($_SESSION['flash'] as $type => $message): ?>
            <div class="alert-container <?= $type ?>">
                <span class="alert-text"><?= $message ?></span>
                <button class="close-alert">&times;</button>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']) ?>
    <?php endif; ?>
</div>