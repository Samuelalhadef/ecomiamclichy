<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/vote.css">
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


    <section class="interface_vote">
        <h1>Vote du <span id="date"></span></h1>
        <div class="content_vote">
            <div class="vote_item">
                <div class="element_today">
                    <?php
                        $servername = "localhost";
                        $username = "root";

                        // On accède à la base de donnée
                        require_once '../../bdd.php';
                        
                        // Vérifiez si l'utilisateur est connecté (par exemple via une session)
                        session_start();
                        // Récupération de la date du jour au format YYYY-MM-DD
                        $dateAujourdhui = date('Y-m-d');

                        // Requête SQL pour récupérer le menu correspondant à la date du jour
                        $sql = "SELECT valeur_element FROM menu WHERE date_menu = :dateAujourdhui";
                        $req = $connexion->prepare($sql);
                        $req->execute(['dateAujourdhui' => $dateAujourdhui]);

                        // Affichage du menu
                        if ($req && $menu = $req->fetch(PDO::FETCH_ASSOC)) {
                            // Stocker les valeurs en session
                            $_SESSION['valeur_element'] = $menu['valeur_element'];
                            
                            echo "<div class='menu_container'>";                    
                                echo "<div class='menu_item'>";
                                    echo "<p class='phrase_vote'> Aujourd'hui, on vote pour:&nbsp;<strong>" . htmlspecialchars($_SESSION['valeur_element']) . "</strong></p>";
                                echo "</div>";
                            echo "</div>";
                        }
                        else {
                            echo "<p>Pas d'élément à voter pour aujourd'hui</p>";
                        }
                        ?>
                </div>
                <div class="launch">
                    <a href="../../Cantine_Ecole/Vote/PHP_Vote_Insertion.php">Lancer de vote</a>
                </div>
            </div>

            <div class="vote_percent">
                <div id="element-titre"></div>
                <div id="graph-container">
                    <div class="bar-wrapper">
                        <div id="like-bar" class="bar"></div>
                        <p class="bar-label">J'aime bien</p>
                    </div>
                    <div class="bar-wrapper">
                        <div id="medium-bar" class="bar"></div>
                        <p class="bar-label">J'aime moyennement</p>
                    </div>
                    <div class="bar-wrapper">
                        <div id="dislike-bar" class="bar"></div>
                        <p class="bar-label">J'aime pas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../../JS/nav.js"></script>
    <script src="../../JS/pourcentage_vote.js"></script>
</body>
</html>



