<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/crud_menu.css">
    <title>Lire le menu</title>
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

    <section class="crud_menu">
        <button class="back"><a href="../../Cantine_Ecole/Menu/HTML_ListeEcole_menu.php"><i class="fa-solid fa-arrow-left"></i>&nbsp;Revenir sur la liste des menus</a></button>

        <?php
            // Vérifier si le paramètre "id" est bien présent
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                die('<p>Menu introuvable (paramètre manquant)</p>');
            }
            require_once '../../bdd.php';
            // Récupérer et sécuriser l'ID du menu
            $id_menu = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id_menu) {
                die('<p>ID du menu invalide</p>');
            }
            // Préparer la requête SQL
            $getmenu = $connexion->prepare(
                'SELECT * FROM menu WHERE id = :id LIMIT 1'
            );
            // Exécuter la requête avec l'ID assaini
            $getmenu->execute(['id' => $id_menu]);
            if ($getmenu->rowCount() === 1) {
                $menu = $getmenu->fetch(PDO::FETCH_ASSOC);
                $dateAujourdhui = date('Y-m-d');

                echo '<div class="elements">';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['entree'] ? ' voted-today' : '') . '">';
                        echo '<h3>Entrée:</h3>';
                        echo '<p>' . htmlspecialchars($menu['entree']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['entree']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['plat'] ? ' voted-today' : '') . '">';
                        echo '<h3>Plat:</h3>';
                        echo '<p>' . htmlspecialchars($menu['plat']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['plat']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['garniture'] ? ' voted-today' : '') . '">';
                        echo '<h3>Garniture:</h3>';
                        echo '<p>' . htmlspecialchars($menu['garniture']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['garniture']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                echo '</div>';
                
                echo '<div class="elements">';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['produit_laitier'] ? ' voted-today' : '') . '">';
                        echo '<h3>Produit laitier:</h3>';
                        echo '<p class="element_readecole">' . htmlspecialchars($menu['produit_laitier']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['produit_laitier']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['dessert'] ? ' voted-today' : '') . '">';
                        echo '<h3>Dessert:</h3>';
                        echo '<p>' . htmlspecialchars($menu['dessert']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['dessert']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="element_item' . ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['divers'] ? ' voted-today' : '') . '">';
                        echo '<h3>Divers:</h3>';
                        echo '<p>' . htmlspecialchars($menu['divers']) . '&nbsp;&nbsp;';
                        if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu['divers']) {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
                        }
                        echo '</p>';
                    echo '</div>';
                echo '</div>';
            }
            else {
                echo '<p>Menu introuvable en base de données</p>';
            }
        ?>
    </section>
    
    <script src="../../JS/nav.js"></script>
    <script src="../../JS/enregistrement.js"></script>
</body>
</html>