<?php
session_start();

// Vérification de la sécurité du token CSRF
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_vote_add']) {
    die("Erreur de sécurité : Token CSRF invalide");
}

// Connexion à la base de données
require_once "../../bdd.php";

// Récupérer la date du menu et l'identifiant de l'utilisateur (à adapter selon votre système)
$date_menu = date('Y-m-d'); // Date du jour
$identifiant = 'test'; // À remplacer par votre méthode d'authentification

// Type de vote
$vote_type = $_POST['vote_type'] ?? null;

if ($vote_type === 'grande_faim' || $vote_type === 'petite_faim') {
    try {
        // Vérifier si un vote existe déjà pour ce menu et cet utilisateur
        $stmt_check = $connexion->prepare("SELECT * FROM vote WHERE date_menu = :date_menu AND identifiant = :identifiant");
        $stmt_check->execute([
            ':date_menu' => $date_menu,
            ':identifiant' => $identifiant
        ]);
        $vote_existant = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($vote_existant) {
            // Mettre à jour le vote existant
            $stmt_update = $connexion->prepare("UPDATE vote 
                SET {$vote_type} = {$vote_type} + 1 
                WHERE date_menu = :date_menu AND identifiant = :identifiant");
            $stmt_update->execute([
                ':date_menu' => $date_menu,
                ':identifiant' => $identifiant
            ]);
        } else {
            // Insérer un nouveau vote
            $stmt_insert = $connexion->prepare("INSERT INTO vote 
                (grande_faim, petite_faim, aime, aime_moyen, aime_pas, date_menu, identifiant, valeur_element) 
                VALUES 
                (:grande_faim, :petite_faim, 0, 0, 0, :date_menu, :identifiant, :valeur_element)");
            
            $stmt_insert->execute([
                ':grande_faim' => $vote_type === 'grande_faim' ? 1 : 0,
                ':petite_faim' => $vote_type === 'petite_faim' ? 1 : 0,
                ':date_menu' => $date_menu,
                ':identifiant' => $identifiant,
                ':valeur_element' => null // À adapter selon votre logique
            ]);
        }

        // Redirection après le vote
        header('Location: ../../Cantine_Ecole/Vote/HTML_Vote_Like.php');
        exit();

    } catch (PDOException $e) {
        die("Erreur lors du vote : " . $e->getMessage());
    }
} else {
    die("Type de vote invalide");
}
?>