<?php
session_start();
require_once "../../bdd.php";

if (!isset($_SESSION['identifiant'])) {
    die("Erreur : Utilisateur non connecté");
}

// Récupérer les données de pesée existantes
try {
    $dateAujourdhui = date('Y-m-d');
    $identifiant = $_SESSION['identifiant'];

    $requete = $connexion->prepare("SELECT * FROM pesee WHERE date_menu = :date_menu AND identifiant = :identifiant ORDER BY id DESC LIMIT 1");
    $requete->execute([':date_menu' => $dateAujourdhui, ':identifiant' => $identifiant]);
    $pesee = $requete->fetch(PDO::FETCH_ASSOC);

    // Valeurs par défaut si aucune pesée n'existe
    if (!$pesee) {
        $pesee = [
            'pesee_restes' => 0,
            'pesee_pain' => 0,
            'moyenne_reste_enfant' => 0,
            'nb_repasprevus' => 0,
            'nb_repasconsommes' => 0,
            'nb_repasconsommesadultes' => 0
        ];
    }

    // Tableau pour stocker les modifications
    $modifications = [];

    // Vérifier les modifications si un formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $champsAVerifier = [
            'pesee_restes', 
            'pesee_pain',
            'moyenne_reste_enfant',
            'nb_repasprevus', 
            'nb_repasconsommes', 
            'nb_repasconsommesadultes'
        ];

        foreach ($champsAVerifier as $champ) {
            // Convertir en nombre pour la comparaison
            $valeurOriginale = isset($pesee[$champ]) ? floatval($pesee[$champ]) : 0;
            $valeurNouvelle = isset($_POST[$champ]) ? floatval($_POST[$champ]) : 0;

            // Comparer les valeurs
            if ($valeurOriginale !== $valeurNouvelle) {
                $modifications[$champ] = [
                    'originale' => $valeurOriginale,
                    'nouvelle' => $valeurNouvelle
                ];
            }
        }
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
    <link rel="stylesheet" href="../../CSS/pesee.css">
    <title>Page d'accueil</title>
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

    <section class="interface_pesee">
        <h1>Pesée du <span id="date"></span></h1>
        <form action="PHP_Pesee_Insertion.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_pesee_add'];?>">
            <div class="pesee_RestesRepas">
                <div class="moyenne">
                    <div class="chiffres">
                        <input type="number" name="moyenne_reste_enfant" value="<?php echo $pesee['moyenne_reste_enfant']; ?>">
                        <h3>g</h3>
                    </div>
                    <h3>Moyenne du poids des restes jetés (par élève)</h3>
                </div>
                <div class="valeurs">
                    <button type="submit">Modifier les valeurs&nbsp;&nbsp;<i class="fa-solid fa-pencil"></i></button>
                </div>
            </div>
            <div class="pesee_RestesRepas">
                <div class="pesee_item">
                    <div class="pesee_restes">
                        <div class="chiffres">
                            <input type="number" name="pesee_restes" value="<?php echo $pesee['pesee_restes']; ?>">
                            <h3>Kg</h3>
                        </div>
                        <h3>Pesée des restes jetés</h3>
                    </div>
                    <div class="pesee_restes">
                        <div class="chiffres">
                            <input type="number" name="pesee_pain" value="<?php echo $pesee['pesee_pain']; ?>">
                            <h3>Kg</h3>
                        </div>
                        <h3>Pesée du pain jetés</h3>
                    </div>
                </div>

                <div class="pesee_repas">
                    <div class="infos_repas">
                        <input class="nb_repas" type="number" name="nb_repasprevus" value="<?php echo $pesee['nb_repasprevus']; ?>">
                        <h3>Nombre de repas prévus</h3>
                    </div>
                    <div class="infos_repas">
                        <input class="nb_repas" type="number" name="nb_repasconsommes" value="<?php echo $pesee['nb_repasconsommes']; ?>">
                        <h3>Nombre de repas consommés</h3>
                    </div>
                    <div class="infos_repas">
                        <input class="nb_repas" type="number" name="nb_repasconsommesadultes" value="<?php echo $pesee['nb_repasconsommesadultes']; ?>">
                        <h3>Repas consommés par les adultes</h3>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script src="../../JS/nav.js"></script>
</body>
</html>


