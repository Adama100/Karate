<?php

use App\App;
use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::UserNotConnect($r->generate('login'));

    $title = "Mon compte";
    $pdo = App::getPDO();
    $user = App::getAuth()->user();

    if(!empty($_POST)) {
        $affilier = $pdo->prepare("SELECT a.created_at, u.* 
            FROM affilier a
            JOIN users u ON u.id = a.user_id
            WHERE u.id = ? AND a.created_at > NOW()
        ");
        $affilier->execute([$user->getId()]);
        $affilier = $affilier->fetch();

        if(!$affilier) {
            // Générer le matricule
            //$matricule = generateMatricule($pdo, $userId);
            $matricule = "ddhd";
            Session::flash('success', "Affiliation réussie. Votre matricule est : " . $matricule);
        } else {
            Session::flash('normal', "Votre affiliation est toujours en cours");
        }
        header('Location: ' . $r->generate('account')); exit;
    }

?>

<div class="container">
    <h5>Bienvenue dans votre espace <?= $user->getUsername() ?></h5>
    <p>
        <form action="" method="post">
            <input type="hidden" name="ok"> <!-- TokenCSRF -->
            <button type="submit" class="btn btn-primary">S'affilier</button>
        </form>
    </p>
</div>