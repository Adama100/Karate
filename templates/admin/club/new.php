<?php

use App\App;
use App\Domain\Builder\FormBuilder;
use App\Domain\Club\Club;
use App\Domain\Club\ClubTable;
use App\Domain\Club\ClubValidator;
use App\Domain\Security\TokenCSRF;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Nouveau club";
    $pdo = App::getPDO();

    $club = new Club();
    $clubTable = new ClubTable($pdo);

    $errors = [];
    if(!empty($_POST)) {

        if (!TokenCSRF::validateToken($_POST['csrf'])) {
            Session::flash('danger', 'Token CSRF invalide');
        }

        $data = array_merge($_POST, $_FILES);
        $v = new ClubValidator($data, $clubTable, $club->getId());
        if($v->validate()) {
            $club
                ->setName(htmlspecialchars($data['name']))
                ->setDescription(htmlspecialchars($data['description']))
                ->setAddress(htmlspecialchars($data['address']))
                ->setMasterName(htmlspecialchars($data['master_name']))
                ->setMasterAdresse(htmlspecialchars($data['master_adresse']))
            ;
            $clubTable->create($club);
            Session::flash('success', 'Le club a bien été créer');  
            header('Location: ' . $r->generate('admin.club.edit', ['id' => $club->getId()])); exit; 
        } else {
            $errors = $v->errors();
        }
    }

    $form = new FormBuilder($club, $errors);

?>

<div class="container">
    <h5>Créer un nouveau club</h5>
    <?php require '_form.php' ?>
</div>