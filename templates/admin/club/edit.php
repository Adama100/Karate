<?php

use App\App;
use App\Domain\Club\ClubTable;
use App\Domain\Club\ClubValidator;
use App\Domain\Security\TokenCSRF;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

    $id = (int)$params['id'];
    $pdo = App::getPDO();

    $clubTable = new ClubTable($pdo);
    try {
        $club = $clubTable->find($id);
    } catch(Exception $e) {

    }

    $errors = [];
    if(!empty($_POST)) {

        if (!TokenCSRF::validateToken($_POST['csrf'])) {
            Session::flash('danger', 'Token CSRF invalide');
        }

        $data = array_merge($_POST, $_FILES);
        $v = new ClubValidator($data, $clubTable, $club->getId());
        if($v->validate()) {

        } else {
            $errors = $v->errors();
        }

    }

?>

<div class="container">
    <h5>Editer le club <span class="text-primary"><?= $club->getName() ?></span></h5>
    <?php require "_form.php" ?>
</div>