<?php

use App\App;
use App\Domain\Application\Builder\Form;
use App\Domain\Application\Security\TokenCsrf;
use App\Domain\Application\Session\Flash;
use App\Domain\Application\Session\PHPSession;
use App\Domain\Auth\Security\UserChecker;
use App\Domain\Club\Club;
use App\Domain\Club\Repository\ClubRepository;
use App\Domain\Club\Validator\ClubValidator;

PHPSession::get();
UserChecker::AdminCheck($r->generate('login'));

    $title = "Nouveau club";
    $pdo = App::getPDO();

    $club = new Club();
    $clubTable = new ClubRepository($pdo);

    $errors = [];
    if(!empty($_POST)) {

        if (!TokenCsrf::validateToken($_POST['csrf'])) {
            Flash::flash('danger', 'Token CSRF invalide');
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
            Flash::flash('success', 'Le club a bien été créer');  
            header('Location: ' . $r->generate('admin.club.edit', ['id' => $club->getId()])); exit; 
        } else {
            $errors = $v->errors();
        }
    }

    $form = new Form($club, $errors);

?>

<div class="container">
    <h5>Créer un nouveau club</h5>
    <?php require '_form.php' ?>
</div>