<?php

session_start();

if (!isset($_SESSION['csrf_users_add']) || empty($_SESSION['csrf_users_add'])){
    $_SESSION['csrf_users_add'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/crud_users.css">
    <title>Ajout d'un profil</title>
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

    <section class="crud_users">
        <form action = "PHP_UsersCreate.php" method = "POST">
            <h2>AJOUTER UN PROFIL</h2>
            <div class="textbox">
                <div class="infos">
                    <div class="info_to">
                        <label for="nom">Nom de l'école</label>
                        <input type="text" name="nom" id="nom" placeholder="Ecole ..." required>
                    </div>
                    <div class="info_to">
                        <label for="adresse">Adresse de l'école</label>
                        <input type="text" name="adresse" id="adresse" placeholder="N° rue/boulevard/avenue" required>
                    </div>
                </div>
                <div class="infos">
                    <div class="info_to">
                        <label for="plat">Identifiant</label>
                        <input type="text" name="identifiant" id="identifiant" placeholder="Identifiant" required>
                    </div>
                    <div class="info_to">
                        <label for="garniture">Mot de passe</label>
                        <input type="text" name="mdp" id="mdp" placeholder="Mot de passe" required>
                    </div>
                </div>
            </div>
            <div class="info_to">
                    <label for="role_profil">Rôle</label>
                    <input type="text" name="role_profil" id="role_profil" placeholder="Admin ou User" required>
                </div>
            <input type="hidden" name="token" value="<?= $_SESSION['csrf_users_add']; ?>">
            <div class="add">
                <button type="submit" name="ajouter" value="Sauvegarder">Sauvegarder</button>
            </div>
        </form>
    </section>
    
    <script src="../../JS/nav.js"></script>
</body>
</html>
