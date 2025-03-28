<?php
session_start();

// Générer le token CSRF si non existant
if (empty($_SESSION['csrf_vote_add'])) {
    $_SESSION['csrf_vote_add'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/vote.css">
    <title>Vote - Faim du Jour</title>
    
</head>
<body>
    <section class="close">
        <a href="../../Cantine_Ecole/Vote/HTML_Interface_Vote.php"><i class="fa-solid fa-close"></i></a>
    </section>
    <section class="vote_faim">
        <h1>C'est l'heure du vote !</h1>
        <h2>Aujourd'hui, j'avais une:</h2>
        <div class="faim">
            <form method="post" action="PHP_Vote_Faim.php">
                <input type="hidden" name="vote_type" value="grande_faim">
                <input type="hidden" name="token" value="<?= $_SESSION['csrf_vote_add']; ?>">
                <button class="faim_item" type="submit">
                    <a href="../../Cantine_Ecole/Vote/HTML_Vote_Like.php">
                        <img src="../../images/GrandeFaim_Texte.gif">
                    </a>
                </button>
            </form>
            
            <form method="post" action="PHP_Vote_Faim.php">
                <input type="hidden" name="vote_type" value="petite_faim">
                <input type="hidden" name="token" value="<?= $_SESSION['csrf_vote_add']; ?>">
                <button class="faim_item" type="submit">
                    <a href="../../Cantine_Ecole/Vote/HTML_Vote_Like.php">
                        <img src="../../images/PetiteFaim_Texte.gif">
                    </a>
                </button>
            </form>
        </div>
    </section>
</body>
</html>