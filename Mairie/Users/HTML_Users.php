<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/user.css">
    <title>Page de gestion de profils</title>
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
    
    <section class="users">
        <h2>Gestion des profils</h2>
        <button class="add-btn"><a href="../../Mairie/Users/HTML_Users_create.php">Ajouter un profil&nbsp;<i class='fa-solid fa-plus'></i></a></button>
        <div class="profiles">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";

            // On accède à la base de données
            require_once '../../bdd.php';

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Requête SQL pour sélectionner et afficher les profils
            $sql = "SELECT * FROM users";
            $req = $connexion->query($sql);
            
            // Vérifie s'il y a des résultats
            if ($req->rowCount() > 0) {
                echo "<div class='profiles-grid'>";
                
                while ($rep = $req->fetch()) {
                    echo "<div class='profile-item'>";

                        echo "<p><strong>Nom de l'école:</strong> " . htmlspecialchars($rep['nom'] ?? '') . "</p>";
                        echo "<p><strong>Adresse:</strong> " . htmlspecialchars($rep['adresse'] ?? '') . "</p>";
                        echo "<p><strong>Identifiant:</strong> " . htmlspecialchars($rep['identifiant'] ?? '') . "</p>";
                        echo "<p><strong>Mot de passe:</strong> " . htmlspecialchars($rep['mdp'] ?? '') . "</p>";
                        echo "<p><strong>Role:</strong> " . htmlspecialchars($rep['role_profil'] ?? '') . "</p>";

                        echo "<div class='profile-actions'>";
                            echo "<button class='edit'><a href='../../Mairie/Users/HTML_Users_update.php?id=" . $rep['id'] . "' class='edit-btn'>Modifier le profil&nbsp;<i class='fa-solid fa-pencil'></i></a></button>";
                            echo "<button class='delete' onclick='openDeleteModal(" . $rep['id'] . ")'>Supprimer&nbsp;<i class='fa-solid fa-trash'></i></button>";
                        echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p class='no-profiles-message'>Aucun profil n'a encore été ajouté.</p>";
            }
            ?>
        </div>
    </section>
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="close">
                <button class="cancel-delete" onclick="closeDeleteModal()">fermer&nbsp;<i class="fa-solid fa-close"></i></button>
            </div>
            <div class="text">
                <p>Êtes-vous sûr de vouloir supprimer ce profil ?</p>
                <p><strong>Cette action est irréversible.</strong></p>
            </div>
            <div class="modal-buttons">
                <button class="confirm-delete" id="confirmDelete">Confirmer la suppression</button>
            </div>
        </div>
    </div>
    
    <script src="../../JS/nav.js"></script>
    <script src="../../JS/delete_popup.js"></script>
</body>
</html>