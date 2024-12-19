<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($description)): ?>
        <meta name="description" content="<?= $description ?? '' ?>">
    <?php endif; ?>
    <?php if(isset($keywords)): ?>
        <meta name="keywords" content="<?= $keywords ?? '' ?>">
    <?php endif; ?>
    <?php if(isset($author)): ?>
        <meta name="author" content="<?= $author ?? '' ?>">
    <?php endif; ?>
    <title><?= isset($title) ? htmlentities($title) : 'PagneMarket' ?> | Karate</title>
    <link rel="icon" href="" type="image/svg+xml">
    <link rel="stylesheet" href="/KRT/public/dist/app.css?v=<?= $cssVersion ?>">
    <script src="/KRT/public/dist/app.js?v=<?= $jsVersion ?>" defer></script>
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $r->generate('index') ?>"><span class="text-danger">KA</span>RA<span class="text-danger">TE</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#index" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="index">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item">
                        <a href="<?= $r->generate('index') ?>" class="nav-link <?php if(isset($nav) && $nav === "index"): ?>active<?php endif; ?>">Accueil</a>
                    </li>
                    <?php if(!isset($_SESSION['USER'])): ?>
                    <li class="nav-item">
                        <a href="<?= $r->generate('login') ?>" class="nav-link <?php if(isset($nav) && $nav === "login"): ?>active<?php endif; ?>">
                            Se connecter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $r->generate('sign') ?>" class="nav-link <?php if(isset($nav) && $nav === "sign"): ?>active<?php endif; ?>">S'inscrire</a>
                    </li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['USER'])): ?>
                    <li class="nav-item">
                        <a href="<?= $r->generate('account') ?>" class="nav-link <?php if(isset($nav) && $nav === "account"): ?>active<?php endif; ?>">
                            Mon compte
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $r->generate('out') ?>" class="nav-link">Deconnexion</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

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

    <main>
        <div class="mt-4">
            <?= $content ?>
        </div>
    </main>

    <footer>
        <?php if(defined('DEBUG_TIME')): ?>
            <div class="p-4">
                Page généré en <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?> ms
            </div>
        <?php endif ?>
    </footer>
</body>
</html>