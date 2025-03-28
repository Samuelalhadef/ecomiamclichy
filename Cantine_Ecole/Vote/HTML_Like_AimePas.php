<?php
session_start();

// Générer le token CSRF si non existant
if (empty($_SESSION['csrf_vote_add'])) {
    $_SESSION['csrf_vote_add'] = bin2hex(random_bytes(32));
}

// Configuration de la base de données
require_once '../../bdd.php';

// Récupération de la date du jour au format YYYY-MM-DD
$dateAujourdhui = date('Y-m-d');

// Requête SQL pour récupérer le menu correspondant à la date du jour
$sql = "SELECT valeur_element FROM menu WHERE date_menu = :dateAujourdhui";
$req = $connexion->prepare($sql);
$req->execute(['dateAujourdhui' => $dateAujourdhui]);

$menu = $req->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Appréciation</title>
    <link rel="stylesheet" href="../../CSS/anim_perso.css">
    <meta http-equiv="refresh" content="4.5;url=../../Cantine_Ecole/Vote/HTML_Vote_Faim.php">
</head>
<body>
    <div class="image-container" style="position: relative;">
        <img class="aaa" src="../../images/Anim_aimepas.gif" alt="Animation">
        <div class="texte">
            <p>Au moins tu as goûté
            <?php 
            if ($menu) {
                $_SESSION['valeur_element'] = $menu['valeur_element'];
                echo "<strong>".htmlspecialchars($menu['valeur_element'])."</strong>";
            } else {
                echo "Pas de menu disponible aujourd'hui";
            }
            ?>
        </h2></p>
        </div>
    </div>
</body>
</html>