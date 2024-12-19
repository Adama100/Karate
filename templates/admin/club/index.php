<?php

use App\App;
use App\Domain\Builder\PaginatedQuery;
use App\Domain\Builder\QueryBuilder;
use App\Domain\Club\Club;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Admin clubs";
    $pdo = App::getPDO();

    $query = (new QueryBuilder($pdo, Club::class))
        ->from('club', 'c')
    ;
    if(!empty($_GET['q'])) {
        $query
            ->where("c.name LIKE :name")
            ->setParam('name', '%' . $_GET['q'] . '%');
    }
    $tableQuery = new PaginatedQuery($query, $_GET);
    [$data, $pages, $error] = $tableQuery->queryFetchRender();

    dump($data);

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

    <h5 class="display-6 mb-3">Gestion des clubs</h5>
    <a href="" class="btn btn-primary mb-3">Nouveau</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th class="lead">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $c): ?>
                <tr>
                    <td>#<?= $c->getId() ?></td>
                    <td><?= $c->getName() ?></td>
                    <td><?= $c->getAddress() ?></td>
                    <td class="d-flex gap-1">
                        <form action="" method="post">
                            <button type="submit" class="btn btn-primary btn-sm">Editer</button>
                        </form>
                        <form action="" method="post" onsubmit="return confirm('Voulez vous vraiment supprimer l\'utilisateur')">
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