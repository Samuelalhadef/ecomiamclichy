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
    <link rel="stylesheet" href="../../CSS/vote.css">
</head>
<body>
    <section class="vote_like">
        <h1>As-tu aimé ce plat ? C'est toi le chef !</h1>
        <h2>Aujourd'hui c'est : 
            <?php 
            if ($menu) {
                $_SESSION['valeur_element'] = $menu['valeur_element'];
                echo htmlspecialchars($menu['valeur_element']);
            } else {
                echo "Pas de menu disponible aujourd'hui";
            }
            ?> !
        </h2>

        <?php if ($menu): ?>
        <div class="like">
            <form method="post" action="PHP_Vote_Like.php">
                <input type="hidden" name="vote_type" value="aime">
                <input type="hidden" name="token" value="<?= $_SESSION['csrf_vote_add']; ?>">
                <button class="like_item" type="submit">
                    <img src="../../images/Aime.gif" alt="J'aime">
                </button>
            </form>
            <form method="post" action="PHP_Vote_Like.php">
                <input type="hidden" name="vote_type" value="aime_moyen">
                <input type="hidden" name="token" value="<?= $_SESSION['csrf_vote_add']; ?>">
                <button class="like_item" type="submit">
                    <img src="../../images/AimeMoyen.gif" alt="Moyen">
                </button>
            </form>
            <form method="post" action="PHP_Vote_Like.php">
                <input type="hidden" name="vote_type" value="aime_pas">
                <input type="hidden" name="token" value="<?= $_SESSION['csrf_vote_add']; ?>">
                <button class="like_item" type="submit">
                    <img src="../../images/AimePas.gif" alt="Je n'aime pas">
                </button>
            </form>
        </div>
        <?php else: ?>
        <p>Aucun vote n'est possible aujourd'hui.</p>
        <?php endif; ?>
    </section>
</body>
</html>