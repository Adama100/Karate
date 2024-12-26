<?php

use App\Domain\Security\UserChecker;
use App\Session;

Session::getSession();
UserChecker::AdminCheck($r->generate('login'));

/*
// Récupérer les utilisateurs affiliés
$stmt = $pdo->query('SELECT id, name, email, matricule FROM users WHERE matricule IS NOT NULL');
$affiliates = $stmt->fetchAll(PDO::FETCH_ASSOC);
 <a href="disable_affiliation.php?id=<?= $user['id'] ?>">Désactiver</a>


 // Désactiver une affiliation
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('UPDATE users SET matricule = NULL WHERE id = ?');
    if ($stmt->execute([$_GET['id']])) {
        echo "Affiliation désactivée avec succès.";
    } else {
        echo "Erreur lors de la désactivation.";
    }
}
*/

?>

<div class="container">
    <h5>Gestion des affiliations</h5>
</div>