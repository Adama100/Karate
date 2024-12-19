<?php

use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::UserNotConnect($r->generate('login'));

    $title = "Mon compte";


    /*
    <li>
                <strong><?= htmlspecialchars($club['name']) ?></strong><br>
                Description : <?= htmlspecialchars($club['description']) ?><br>
                Adresse : <?= htmlspecialchars($club['address']) ?><br>
                Maître : <?= htmlspecialchars($club['master_name']) ?><br>
                Créé le : <?= htmlspecialchars($club['created_at']) ?><br>
            </li>
    */
?>

<div class="container">
    <h5>Bienvenue dans votre espace</h5>


    <p>
        Ajout des clubs
        Afficher la liste des clubs
    </p>
</div>