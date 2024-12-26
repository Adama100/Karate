<?php

    namespace App\Helper;

    class MatriculeHelper {

        /*
        

function generateMatricule($pdo, $userId) {
    // Récupérer l'année actuelle
    $year = date('Y');
    
    // Générer un identifiant incrémenté
    $stmt = $pdo->query('SELECT COUNT(id) + 1 AS matricule_count FROM users WHERE matricule IS NOT NULL');
    $count = $stmt->fetchColumn();
    
    // Format du matricule
    $matricule = sprintf('KAR-%s-%05d', $year, $count);
    
    // Vérifier l'unicité (même si improbable dans ce cas)
    $stmt = $pdo->prepare('SELECT id FROM users WHERE matricule = ?');
    $stmt->execute([$matricule]);
    if ($stmt->fetch()) {
        // Si le matricule existe déjà, appeler la fonction récursivement
        return generateMatricule($pdo, $userId);
    }
    
    // Mise à jour du matricule dans la base de données
    $stmt = $pdo->prepare('UPDATE users SET matricule = ? WHERE id = ?');
    $stmt->execute([$matricule, $userId]);

    return $matricule;
}

// Générer le matricule
$matricule = generateMatricule($pdo, $userId);
        */
    }