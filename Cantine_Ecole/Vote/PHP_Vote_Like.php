<?php
session_start();

// Vérification de la sécurité du token CSRF
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_vote_add']) {
    die("Erreur de sécurité : Token CSRF invalide");
}

// Connexion à la base de données
require_once "../../bdd.php";

// Récupérer la date du menu et l'identifiant de l'utilisateur
$date_menu = date('Y-m-d');

// IMPORTANT : Remplacer par votre mécanisme d'authentification
$identifiant = isset($_SESSION['identifiant']) ? $_SESSION['identifiant'] : 'anonyme';

// Type de vote
$vote_type = $_POST['vote_type'] ?? null;

if (in_array($vote_type, ['aime', 'aime_moyen', 'aime_pas'])) {
    try {
        // Récupérer le menu du jour pour stocker sa valeur
        $stmt_menu = $connexion->prepare("SELECT valeur_element FROM menu WHERE date_menu = :date_menu");
        $stmt_menu->execute([':date_menu' => $date_menu]);
        $menu = $stmt_menu->fetch(PDO::FETCH_ASSOC);
        $valeur_element = $menu ? $menu['valeur_element'] : null;

        // Vérifier si un vote existe déjà
        $stmt_check = $connexion->prepare("SELECT * FROM vote WHERE date_menu = :date_menu AND identifiant = :identifiant");
        $stmt_check->execute([
            ':date_menu' => $date_menu,
            ':identifiant' => $identifiant
        ]);
        $vote_existant = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($vote_existant) {
            // Mise à jour du vote existant
            $stmt_update = $connexion->prepare("UPDATE vote 
                SET {$vote_type} = {$vote_type} + 1 
                WHERE date_menu = :date_menu AND identifiant = :identifiant");
            $stmt_update->execute([
                ':date_menu' => $date_menu,
                ':identifiant' => $identifiant
            ]);
        } else {
            // Insertion d'un nouveau vote
            $stmt_insert = $connexion->prepare("INSERT INTO vote 
                (aime, aime_moyen, aime_pas, date_menu, identifiant, valeur_element) 
                VALUES 
                (:aime, :aime_moyen, :aime_pas, :date_menu, :identifiant, :valeur_element)");
            
            $stmt_insert->execute([
                ':aime' => $vote_type === 'aime' ? 1 : 0,
                ':aime_moyen' => $vote_type === 'aime_moyen' ? 1 : 0,
                ':aime_pas' => $vote_type === 'aime_pas' ? 1 : 0,
                ':date_menu' => $date_menu,
                ':identifiant' => $identifiant,
                ':valeur_element' => $valeur_element
            ]);
        }

        // Redirection basée sur le type de vote
        switch ($vote_type) {
            case 'aime':
                header('Location: ../../Cantine_Ecole/Vote/HTML_Like_Aime.php');
                break;
            case 'aime_moyen':
                header('Location: ../../Cantine_Ecole/Vote/HTML_Like_AimeMoyen.php');
                break;
            case 'aime_pas':
                header('Location: ../../Cantine_Ecole/Vote/HTML_Like_AimePas.php');
                break;
        }
        exit();

    } catch (PDOException $e) {
        die("Erreur lors du vote : " . $e->getMessage());
    }
} else {
    die("Type de vote invalide");
}
?>