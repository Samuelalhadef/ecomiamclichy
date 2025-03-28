<?php
// Start the session to access user information
session_start();

// Include database connection
require_once '../../bdd.php';

try {
    // Check if the user is logged in and has an identifiant
    if (!isset($_SESSION['identifiant'])) {
        // Redirect or show an error if user is not logged in
        die("Erreur : Utilisateur non connecté");
    }

    // Get today's date in YYYY-MM-DD format
    $dateAujourdhui = date('Y-m-d');

    // Check if the user has already voted today
    $checkVoteSql = "SELECT COUNT(*) FROM vote 
                     WHERE identifiant = :identifiant 
                     AND date_menu = :date_menu";
    $checkVoteReq = $connexion->prepare($checkVoteSql);
    $checkVoteReq->execute([
        'identifiant' => $_SESSION['identifiant'],
        'date_menu' => $dateAujourdhui
    ]);

    // If user has already voted today, redirect or show message
    if ($checkVoteReq->fetchColumn() > 0) {
        // Redirect to a page indicating they've already voted
        header("Location: ../../Cantine_Ecole/Vote/HTML_Vote_Faim.php?error=already_voted");
        exit();
    }

    // Retrieve the valeur_element for today's menu
    $menuSql = "SELECT valeur_element FROM menu WHERE date_menu = :date_menu";
    $menuReq = $connexion->prepare($menuSql);
    $menuReq->execute(['date_menu' => $dateAujourdhui]);
    $menuResult = $menuReq->fetch(PDO::FETCH_ASSOC);

    if (!$menuResult) {
        die("Erreur : Pas de menu trouvé pour aujourd'hui");
    }

    // Prepare SQL to insert a new vote record
    $insertVoteSql = "INSERT INTO vote (
        identifiant, 
        date_menu, 
        valeur_element, 
        grande_faim, 
        petite_faim, 
        aime, 
        aime_moyen, 
        aime_pas
    ) VALUES (
        :identifiant, 
        :date_menu, 
        :valeur_element, 
        0, 
        0, 
        0, 
        0, 
        0
    )";

    // Prepare and execute the insert statement
    $insertVoteReq = $connexion->prepare($insertVoteSql);
    $insertVoteReq->execute([
        'identifiant' => $_SESSION['identifiant'],
        'date_menu' => $dateAujourdhui,
        'valeur_element' => $menuResult['valeur_element']
    ]);

    // Redirect to the voting page
    header("Location: ../../Cantine_Ecole/Vote/HTML_Vote_Faim.php");
    exit();

} catch(PDOException $e) {
    // Handle any database errors
    error_log("Erreur d'insertion de vote : " . $e->getMessage());
    header("Location: HTML_Interface_Vote_Faim.php?error=database_error");
    exit();
}
?>