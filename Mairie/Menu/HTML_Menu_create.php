<?php
session_start();

if (!isset($_SESSION['csrf_menu_add']) || empty($_SESSION['csrf_menu_add'])) {
    $_SESSION['csrf_menu_add'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/crud_menu.css">
    <title>Ajout d'un menu</title>
</head>
<body>
    <header>
        <a href="../../Mairie/HTML_Admin_Home.php"><img class="logo" src="../../images/logo.png"></a>
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

    <section class="crud_menu">
        <h1>AJOUTER UN MENU</h1>
        <form action="PHP_MenuCreate.php" method="POST">
            <div class="textbox">
                <div class="infos">
                    <div class="info_to">
                        <label for="date_menu">Date du menu</label>
                        <input type="date" name="date_menu" id="date_menu" required>
                    </div>
                    <div class="info_to">
                        <label for="entree">Entrée</label>
                        <input type="text" name="entree" id="entree" placeholder="Entrée" required oninput="mettreAJourValeurElement()">
                    </div>
                    <div class="info_to">
                        <label for="plat">Plat</label>
                        <input type="text" name="plat" id="plat" placeholder="Plat" required oninput="mettreAJourValeurElement()">
                    </div>
                    <div class="info_to">
                        <label for="garniture">Garniture</label>
                        <input type="text" name="garniture" id="garniture" placeholder="Garniture" required oninput="mettreAJourValeurElement()">
                    </div>
                </div>
                <div class="infos">
                    <div class="info_to">
                        <label for="nom_menu">Nom du menu</label>
                        <input type="text" name="nom_menu" id="nom_menu" placeholder="menu_JJMMAAAA" required>
                    </div>
                    <div class="info_to">
                        <label for="produit_laitier">Produit Laitier</label>
                        <input type="text" name="produit_laitier" id="produit_laitier" placeholder="Produit laitier" required oninput="mettreAJourValeurElement()">
                    </div>
                    <div class="info_to">
                        <label for="dessert">Dessert</label>
                        <input type="text" name="dessert" id="dessert" placeholder="Dessert" required oninput="mettreAJourValeurElement()">
                    </div>
                    <div class="info_to">
                        <label for="divers">Divers</label>
                        <input type="text" name="divers" id="divers" placeholder="Divers" required oninput="mettreAJourValeurElement()">
                    </div>
                </div>
            </div>
            <div class="choix">
                <label for="element_vote">Choisir l'élément à voter aujourd'hui :</label>
                <select id="element_vote" name="element_vote" onchange="mettreAJourValeurElement()">
                    <option value="">--Choisir--</option>
                    <option value="entree">Entrée</option>
                    <option value="plat">Plat</option>
                    <option value="garniture">Garniture</option>
                    <option value="produit_laitier">Produit Laitier</option>
                    <option value="dessert">Dessert</option>
                    <option value="divers">Divers</option>
                </select>
            </div>

            <input type="hidden" name="valeur_element" id="valeur_element">
            <input type="hidden" name="token" value="<?= $_SESSION['csrf_menu_add']; ?>">
            <button class="add" type="submit" name="ajouter" value="Sauvegarder">Sauvegarder</button>
        </form>
    </section>

    <script>
    function mettreAJourValeurElement() {
        let select = document.getElementById("element_vote").value;
        let valeurElementInput = document.getElementById("valeur_element");

        if (select) {
            let champSelectionne = document.getElementById(select);
            valeurElementInput.value = champSelectionne ? champSelectionne.value : "";
        } else {
            valeurElementInput.value = "";
        }
    }
    </script>

    <script src="../../JS/nav.js"></script>
</body>
</html>
