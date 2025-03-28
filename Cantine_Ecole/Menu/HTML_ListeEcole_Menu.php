<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/liste_menus.css">
    <title>Liste des menus</title>
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

    <section class="liste_menus">
        <h1>GESTION DES MENUS</h1>
        
        <div>
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";

                //On accède à la base de donnée
                require_once '../../bdd.php';
                
                session_start();
                
                // Requête SQL pour sélectionner les menus
                $sql = "SELECT * FROM menu ORDER BY date_menu ASC";
                $req = $connexion->query($sql);
                
                // Récupérer tous les menus
                $menus = $req->fetchAll(PDO::FETCH_ASSOC);
                
                // Définir la semaine en cours
                $semaineActuelle = date('W');
                $anneeActuelle = date('Y');
                
                // Définir le nombre total de semaines à charger
                $totalSemaines = 7;
                $semainesParPage = 1;
                $semaineDepart = $semaineActuelle;
                $pageActuelle = 1;
                $totalPages = ceil($totalSemaines / $semainesParPage);
                
                echo '<div class="content_gestion_menu">';
                    echo '<div class="navigation-pagination">';
                        echo '<div class="content_calendrier">';
                            echo '<button class="calendar" id="open-calendar"><i class="fa-solid fa-calendar-days"></i>&nbsp;Calendrier</button>';
                            echo '<input type="date" id="date-picker">';
                            echo '<p id="selected-date"></p>';
                        echo '</div>';
                        echo '<div class="pagination">';
                            echo '<button class="nav-btn" id="prev-week">Semaine précédente</button>';
                            echo '<span id="semaine-indicator">Semaine ' . $semaineActuelle . ' - ' . $anneeActuelle . '</span>';
                            echo '<button class="nav-btn" id="next-week">Semaine suivante</button>';
                        echo '</div>';
                    echo '</div>';

                    // Ajout des contrôles de pagination par page
                    echo '<div class="pagination-controls">';
                        echo '<button id="first-page" class="page-control"><i class="fa-solid fa-angles-left"></i></button>';
                        echo '<button id="prev-page" class="page-control"><i class="fa-solid fa-angle-left"></i></button>';
                        
                        // Génération des boutons numérotés pour les pages
                        $totalPages = ceil($totalSemaines / $semainesParPage);
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = ($i == $pageActuelle) ? 'active' : '';
                            echo '<button class="page-num ' . $activeClass . '" data-page="' . $i . '">' . $i . '</button>';
                        }
                        
                        echo '<button id="next-page" class="page-control"><i class="fa-solid fa-angle-right"></i></button>';
                        echo '<button id="last-page" class="page-control"><i class="fa-solid fa-angles-right"></i></button>';
                        echo '<span id="page-range">' . (($pageActuelle-1) * $semainesParPage + 1) . '-' . min($pageActuelle * $semainesParPage, $totalSemaines) . '</span>';
                    echo '</div>';
                                        
                    echo '<div class="semaines-container" id="semaines-container">';
                        // Boucle pour toutes les semaines (passées et futures)
                        for ($i = 0; $i < $totalSemaines; $i++) {
                            $numSemaine = $semaineDepart + $i;
                            $annee = $anneeActuelle;
                            
                            // Gérer le changement d'année
                            if ($numSemaine <= 0) {
                                $numSemaine = 52 + $numSemaine;
                                $annee--;
                            }
                            elseif ($numSemaine > 52) {
                                $numSemaine = $numSemaine - 52;
                                $annee++;
                            }
                            
                            // Définir une classe spéciale pour la semaine actuelle
                            $isCurrentWeek = ($numSemaine === $semaineActuelle && $annee === $anneeActuelle) ? ' current-week' : '';
                            
                            echo "<div class='semaine{$isCurrentWeek}' data-semaine='{$numSemaine}' data-annee='{$annee}' data-index='{$i}'>";
                                echo "<h3>Semaine " . $numSemaine . " - " . $annee . "</h3>";
                                echo "<div class='jours_semaine'>";
                                    
                                    // Pour chaque jour de la semaine (5 jours - lundi à vendredi)
                                    for ($jour = 1; $jour <= 5; $jour++) {
                                        // Calculer la date pour ce jour
                                        $date = new DateTime();
                                        $date->setISODate($annee, $numSemaine, $jour);
                                        $dateStr = $date->format('Y-m-d');
                                        $jourNom = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'][$jour-1];
                                        
                                        $todayStyle = ($dateStr === date('Y-m-d')) 
                                        ? "style='background-color: #63eebb;'" 
                                        : "";
                                    
                                        // Définir une classe spéciale pour aujourd'hui
                                        $isToday = ($dateStr === date('Y-m-d')) ? ' today' : '';

                                        echo "<div class='jour{$isToday}' data-date='{$dateStr}'{$todayStyle}>";
                                            echo "<h3>{$jourNom} " . $date->format('d/m') . "</h3>";
                                            
                                            // Chercher un menu pour cette date
                                            $menuTrouve = false;
                                            $dateAujourdhui = date('Y-m-d');
                                            foreach ($menus as $menu) {
                                                if (isset($menu['date_menu']) && $menu['date_menu'] == $dateStr) {
                                                    $menuTrouve = true;
                                                    echo "<div class='menu_bloc'>";
                                                        echo "<div class='menu'>";
                                                        
                                                        // Liste des catégories du menu
                                                        $categories = [
                                                            "entree" => "Entrée",
                                                            "plat" => "Plat",
                                                            "garniture" => "Garniture",
                                                            "produit_laitier" => "Produit laitier",
                                                            "dessert" => "Dessert",
                                                            "divers" => "Divers"
                                                        ];
                                                        
                                                        // Boucle sur chaque catégorie pour afficher dynamiquement le menu
                                                        foreach ($categories as $cle => $label) {
                                                            echo "<div class='menu_item'>";
                                                            echo "<p>{$label}:&nbsp;</p>";
                                                            echo "<p>" . htmlspecialchars($menu[$cle]) . "&nbsp;&nbsp;</p>";
                                                            
                                                            // Vérifier si cet élément est voté aujourd’hui
                                                            if ($menu['date_menu'] == $dateAujourdhui && $menu['valeur_element'] == $menu[$cle]) {
                                                                echo "<div class='vote-icon'>";
                                                                echo "<i class='fa-solid fa-check-circle' style='color: green;'></i>";
                                                                echo "</div>";
                                                            }
                                                            
                                                            echo "</div>";
                                                        }
                                            
                                                        echo "</div>";
                                                    echo "</div>"; 
                                            
                                                    // Bouton voir le menu
                                                    echo "<div class='bouton_read'>";
                                                    echo "<button><a href='../../Cantine_Ecole/Menu/HTML_MenuEcole_Read.php?id=" . $menu['id'] . "'>Voir le menu&nbsp;&nbsp;<i class='fa-solid fa-pencil'></i></a></button>";
                                                    echo "</div>";
                                            
                                                    break; // On sort de la boucle après avoir affiché un menu pour ce jour
                                                }
                                            }
                                            if (!$menuTrouve) {
                                                echo "<div class='menu_vide'>";
                                                    echo "<p>Pas de menu pour ce jour</p>";
                                                echo "</div>";
                                            }
                                        echo "</div>";
                                    }
                                echo "</div>";
                            echo "</div>";
                        }
                    echo '</div>'; 
                echo '</div>';
                include '../../JS/calendrier.php';
            ?>
        </div>
    </section>

    <script src="../../JS/nav.js"></script>
    
</body>
</html>



