<?php

use App\Session;

Session::getSession();

/*
    $amount = 5000; // Montant de l'abonnement en FCFA
    $paymentRef = uniqid('PAY-'); // Référence unique du paiement
    $userId = $_SESSION['user']['id'];

    // Simuler une réponse de paiement Mobile Money (dans un vrai projet, utiliser une API)
    $isPaymentSuccessful = true; // Supposons que le paiement est réussi

    if ($isPaymentSuccessful) {
        // Calculer la nouvelle date d'expiration
        $currentDate = new DateTime();
        $newExpiryDate = $currentDate->modify('+1 year')->format('Y-m-d');

        // Mettre à jour l'abonnement dans la base de données
        $stmt = $pdo->prepare("
            UPDATE users
            SET subscription_status = 'active', subscription_expiry = ?
            WHERE id = ?
        ");
        $stmt->execute([$newExpiryDate, $userId]);

        // Afficher un message de succès
        echo "Abonnement réussi ! Votre abonnement expirera le : " . $newExpiryDate;
    } else {
        echo "Échec du paiement. Veuillez réessayer.";
    }


<h1>Abonnement à l'affiliation</h1>
    <form method="POST">
        <p>Montant : 5000 FCFA</p>
        <p>Référence de paiement : Mobile Money</p>
        <button type="submit">Payer et s'affilier</button>
    </form>
*/

/*
function checkSubscription($userId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT subscription_status, subscription_expiry
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['subscription_status'] === 'active') {
        $expiryDate = new DateTime($user['subscription_expiry']);
        $today = new DateTime();

        if ($expiryDate >= $today) {
            return true; // L'abonnement est valide
        } else {
            // Mettre à jour le statut si expiré
            $update = $pdo->prepare("
                UPDATE users
                SET subscription_status = 'inactive'
                WHERE id = ?
            ");
            $update->execute([$userId]);
        }
    }
    return false; // L'abonnement n'est pas valide
}
// Vérifier l'abonnement
if (!checkSubscription($_SESSION['user']['id'], $pdo)) {
    die('Votre abonnement est expiré ou inactif. Veuillez vous abonner pour accéder à cette fonctionnalité.');
}
*/