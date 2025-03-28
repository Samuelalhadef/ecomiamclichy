<?php
session_start();
require_once "../../bdd.php";

if (!isset($_SESSION['identifiant'])) {
    die("Erreur : Utilisateur non connecté");
}

// Récupérer les données de pesée existantes depuis la base de données
try {
    $dateAujourdhui = date('Y-m-d');  // Vous pouvez modifier cette date si nécessaire
    $identifiant = $_SESSION['identifiant'];

    // Requête pour obtenir les données de la pesée
    $requete = $connexion->prepare("SELECT * FROM pesee WHERE date_menu = :date_menu AND identifiant = :identifiant ORDER BY id DESC LIMIT 1");
    $requete->execute([':date_menu' => $dateAujourdhui, ':identifiant' => $identifiant]);
    $pesee = $requete->fetch(PDO::FETCH_ASSOC);

    // Si aucune donnée n'est trouvée, initialiser avec des valeurs par défaut
    if (!$pesee) {
        $pesee = [
            'pesee_restes' => 0,
            'pesee_pain' => 0,
            'nb_repasprevus' => 0,
            'nb_repasconsommes' => 0,
            'nb_repasconsommesadultes' => 0
        ];
    }
} catch (PDOException $e) {
    die("Erreur de récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/crud_pesee.css">
    <title>Page modification valeurs</title>
</head>
<body>
    <header>
        <a href="../../Cantine_Ecole/HTML_User_Home.php"><img class="logo" src="../../images/logo.png"></a>
        <p id="date"></p>
        <div>
            <div class="off-screen-menu">
                <ul class="off-screen-menu-item">
                    <li><a href="../../Cantine_Ecole/HTML_User_Home.php">PAGE D'ACCUEIL</a></li>
                    <li><a href="../../Cantine_Ecole/Menu/HTML_ListeEcole_Menu.php">GESTION DES MENUS</a></li>
                    <li><a href="../../Cantine_Ecole/Vote/HTML_Interface_Vote.php">VOTE DU JOUR</a></li>
                    <li><a href="../../Cantine_Ecole/Pesee/HTML_Interface_Pesee.php">PESEE DU JOUR</a></li>
                    <li><a href="../../Cantine_Ecole/Synthese/HTML_Synthese.php">SYNTHESE</a></li>
                </ul>
                <ul class="off-screen-menu-plus">
                    <li class="off-screen-menu-item-text"><a href="../../Login/HTML_Login.php">Se déconnecter&nbsp;&nbsp;</a><i class="fa-solid fa-right-from-bracket"></i></li>
                </ul>
            </div>
            <nav>
                <p>MENU&nbsp;&nbsp;</p>
                <div class="ham-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <section class="crud_pesee">
        <form action="PHP_PeseeUpdate.php" method="POST">
            <div class="textbox">
                <div class="infos">
                    <div class="info_to">
                        <label for="moyenne_reste_enfant">Moyenne des restes jetés par enfant</label>
                        <input type="number" name="moyenne_reste_enfant" id="moyenne_reste_enfant" value="<?= $pesee['moyenne_reste_enfant'] ?? 0 ?>">
                    </div>
                    <div class="info_to">
                        <label for="nb_repasprevus">Nombre de repas prévu:</label>
                        <input type="number" name="nb_repasprevus" id="nb_repasprevus" value="<?= $pesee['nb_repasprevus'] ?? 0 ?>">
                    </div>
                    <div class="info_to">
                        <label for="nb_repasconsommes">Nombre de repas consommés:</label>
                        <input type="number" name="nb_repasconsommes" id="nb_repasconsommes" value="<?= $pesee['nb_repasconsommes'] ?? 0 ?>">
                    </div>
                    <div class="info_to">
                        <label for="nb_repasconsommesadultes">Repas consommés par les adultes:</label>
                        <input type="number" name="nb_repasconsommesadultes" id="nb_repasconsommesadultes" value="<?= $pesee['nb_repasconsommesadultes'] ?? 0 ?>">
                    </div>
                </div>
                <div class="infos">
                    <div class="info_to">
                        <label for="pesee_restes">Poids des restes jetés en kilos:</label>
                        <input class="restes" type="number" name="pesee_restes" id="pesee_restes" value="<?= $pesee['pesee_restes'] ?? 0 ?>">
                    </div>
                    <div class="info_to">
                        <label for="pesee_pain">Poids du pain jeté en kilos:</label>
                        <input class="restes" type="number" name="pesee_pain" id="pesee_pain" value="<?= $pesee['pesee_pain'] ?? 0 ?>">
                    </div>
                </div>
            </div>
            <input type="hidden" name="token" value="<?= $_SESSION['csrf_pesee_add']; ?>">
            <button type="submit" name="modifier" value="Sauvegarder">Sauvegarder</button>
        </form>
    </section>


    <script src="../../JS/nav.js"></script>
</body>
</html>
