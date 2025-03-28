<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/home.css">
    <title>Page d'accueil</title>
</head>
<body>
    <header>
        <a href="../../Mairie/HTML_Admin_Home.php"><img class="logo" src="../images/logo.png"></a>
        <p id="date"></p>
        <div>
            <div class="off-screen-menu">
                <ul class="off-screen-menu-item">
                    <li><a href="../../Mairie/HTML_Admin_Home.php">PAGE D'ACCUEIL</a></li>
                    <li><a href="../../Mairie/Menu/HTML_Liste_Menu.php">GESTION DES MENUS</a></li>
                    <li><a href="../../Mairie/Users/HTML_Users.php">GESTION DES PROFILS</a></li>
                    <li><a href="../../Mairie/Synthese/HTML_Synthese.php">SYNTHESE</a></li>
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
    
    <section class="accueil">
        <h1>BONJOUR,</h1>
        <?php
            $servername = "localhost";
            $username = "root";

            // On accède à la base de donnée
            require_once '../bdd.php';
            
            // Vérifiez si l'utilisateur est connecté (par exemple via une session)
            session_start();
            
            // On vérifie si l'utilisateur est connecté
            if(isset($_SESSION['identifiant'])) {
                // Affichage de l'identifiant stocké dans la session
                echo "<h2>" . htmlspecialchars($_SESSION['identifiant']) . "</h2>";
            }
            else {
                echo "<p>Vous n'êtes pas connecté ou la session a expiré.</p>";
            }
        ?>
    </section>

    <section class="content_accueil">
        <div class="content_menu">
            <h3>MENU DU JOUR</h3>
            <?php
                // Récupération de la date du jour au format YYYY-MM-DD
                $dateAujourdhui = date('Y-m-d');

                // Requête SQL pour récupérer le menu correspondant à la date du jour
                $sql = "SELECT entree, plat, garniture, produit_laitier, dessert, divers FROM menu WHERE date_menu = :dateAujourdhui";
                $req = $connexion->prepare($sql);
                $req->execute(['dateAujourdhui' => $dateAujourdhui]);

                // Affichage du menu
                if ($req && $menu = $req->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='menu'>";
                    
                    // Liste des catégories du menu
                    $categories = [
                        'Entrée' => 'entree',
                        'Plat' => 'plat',
                        'Garniture' => 'garniture',
                        'Produit laitier' => 'produit_laitier',
                        'Dessert' => 'dessert',
                        'Divers' => 'divers'
                    ];

                    // Affichage dynamique
                    foreach ($categories as $nom => $cle) {
                        $valeur = $menu[$cle]; // Valeur non échappée pour la comparaison
                        $valeurAffichage = htmlspecialchars($valeur); // Valeur échappée pour l'affichage
                        
                        echo "<div class='menu_item'>";
                            echo "<p>{$nom}:&nbsp;</p>";
                            echo "<p>{$valeurAffichage} ";
                            echo "</p>";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                } 
                else {
                    echo "<p>Pas de menu prévu pour aujourd'hui (" . date('d/m/Y') . ")</p>";
                }
            ?>
            <button><a href="../../Mairie/Menu/HTML_Liste_Menu.php">Gestion des menus&nbsp;<i class="fa-solid fa-arrow-right"></i></a></button>
        </div>
        <div class="users">
            <h3>GESTION DES PROFILS</h3>
            <button><a href="../../Mairie/Users/HTML_Users.php">Détails&nbsp;<i class="fa-solid fa-arrow-right"></i></a></button>
        </div>
        <div class="synthese">
            <h3>SYNTHESE</h3>
            <button><a href="../../Mairie/Synthese/HTML_synthese.php">Détails&nbsp;<i class="fa-solid fa-arrow-right"></i></a></button>
        </div>
        </section>
    <script src="../../JS/nav.js"></script>
</body>
</html>