<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/synthese.css">
    <title>Synthèse des Données</title>
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

    <section class="synthese">
        <form action="export_excel.php" method="post">
            <h2>SYNTHESE DES DONNEES</h2>
            <div class="export">
                <button type="submit">Exporter en Excel&nbsp;&nbsp;<i class="fa-solid fa-arrow-up-from-bracket"></i></button>
            </div>
            

            <div id="loading">Chargement des données...</div>
            <div class="table-container">
                <div id="excel-output"></div>
            </div>
        </form>
    </section>

    <script src="../../JS/nav.js"></script>
    <script src="../../JS/synthese.js"></script>
</body>
</html>