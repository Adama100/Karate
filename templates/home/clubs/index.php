<?php

use App\Domain\Application\Session\PHPSession;
use App\Infrastructure\Breadcrumb\Breadcrumb;

PHPSession::get();

    $title = 'Liste des clubs';
    $nav = 'clubs';

    $crumb = new Breadcrumb();
    $crumb
        ->addCrumb('Accueil', $r->generate('index'))
        ->addCrumb('Clubs', $r->generate(''));

?>

<?= $crumb->render() ?>

<div class="container">
    <h5>Liste des clubs</h5>
</div>