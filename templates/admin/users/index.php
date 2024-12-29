<?php

use App\App;
use App\Domain\Application\Builder\PaginatedQuery;
use App\Domain\Application\Builder\QueryBuilder;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Security\UserChecker;

PHPSession::get();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Administration | Utilisateurs";
    $nav = "admin.users";
    $pdo = App::getPDO();

    $query = (new QueryBuilder($pdo, User::class))
        ->from('users', 'u')
        ->where("u.role != 'super admin'")
    ;
    if(!empty($_GET['q'])) {
        $query
            ->where("u.username LIKE :name")
            ->setParam('name', '%' . $_GET['q'] . '%');
    }
    $tableQuery = new PaginatedQuery($query, $_GET, 30);
    [$users, $pages, $error] = $tableQuery->queryFetchRender();

?>

<div class="container">
    <?php if($error): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif ?>

    <form action="" method="get">
        <div class="form-group">
            <input type="text" class="form-control" name="q" placeholder="Rechercher par nom .." value="<?php if(isset($_GET['q'])): ?><?= $_GET['q'] ?><?php endif; ?>">
        </div>
        <button class="btn btn-primary mt-2 mb-3">Rechercher</button>
    </form>

    <h5 class="display-6 mb-3">Gestion des utilisateurs</h5>
    <a href="<?= $r->generate('admin.user.new') ?>" class="btn btn-primary mb-3">Nouveau</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th class="lead">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>#<?= $u->getId() ?></td>
                    <td><?= $u->getUsername() ?></td>
                    <td><?= $u->getEmail() ?></td>
                    <td class="d-flex gap-1">
                        <form action="<?= $r->generate('admin.user.edit') ?>" method="post">
                            <button type="submit" class="btn btn-primary btn-sm">Editer</button>
                        </form>
                        <form action="<?= $r->generate('admin.user.delete') ?>" method="post" onsubmit="return confirm('Voulez vous vraiment supprimer l\'utilisateur')">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center gap-1 my-5">
        <?= $tableQuery->previousLink($pages) ?>
        <?php $tableQuery->chiffreLink($pages) ?>
        <?= $tableQuery->nextLink($pages) ?>
    </div>
</div>
