<?php
    session_start();

    if (!isset($_SESSION['csrf_menu_add']) || empty($_SESSION['csrf_menu_add'])){
        $_SESSION['csrf_menu_add'] = bin2hex(random_bytes(32));
    }

    $id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $field = isset($_GET['field']) ? $_GET['field'] : '';

    $allowed_fields = ['entree', 'plat', 'garniture', 'produit_laitier', 'dessert', 'divers', 'date_menu', 'nom_menu'];

    if (!in_array($field, $allowed_fields)) {
        die('<p>Champ non valide pour la modification</p>');
    }

    $current_value = '';
    if ($id_menu > 0) {
        require_once '../../bdd.php';
        $query = $connexion->prepare("SELECT * FROM menu WHERE id = :id");
        $query->execute(['id' => $id_menu]);
        $current_menu = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($current_menu) {
            $current_value = $current_menu[$field];
        } else {
            header('Location: ../../Mairie/HTML_Liste_Menu.php');
            exit();
        }
    }

    $field_labels = [
        'entree' => 'Entrée',
        'plat' => 'Plat',
        'garniture' => 'Garniture',
        'produit_laitier' => 'Produit laitier',
        'dessert' => 'Dessert',
        'divers' => 'Divers',
        'date_menu' => 'Date du menu',
        'nom_menu' => 'Nom du menu'
    ];

    $field_label = $field_labels[$field];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/crud_menu.css">
    <title>Modifier <?= $field_label ?></title>
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
        <form action="PHP_MenuUpdate.php" method="POST" class="update">
            <h2>MODIFIER <?= strtoupper($field_label) ?></h2>
            <div class="textbox">
                <div class="infos">
                    <div class="info_to">
                        <label for="field_value"><?= $field_label ?></label>
                        <?php if ($field === 'date_menu'): ?>
                            <input type="date" name="field_value" id="field_value" value="<?= htmlspecialchars($current_value) ?>" required>
                        <?php else: ?>
                            <input type="text" name="field_value" id="field_value" value="<?= htmlspecialchars($current_value) ?>" required>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id_menu" value="<?= $id_menu ?>">
            <input type="hidden" name="field_name" value="<?= $field ?>">
            <input type="hidden" name="valeur_element" id="valeur_element" value="<?= htmlspecialchars($current_value) ?>">
            <input type="hidden" name="token" value="<?= $_SESSION['csrf_menu_add']; ?>">
            <button type="submit" name="modifier" value="Modifier">Modifier</button>
        </form>
    </section>
    
    <script src="../../JS/nav.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let fieldInput = document.getElementById("field_value");
        let valeurElementInput = document.getElementById("valeur_element");

        let currentValue = fieldInput.value; // Valeur initiale

        fieldInput.addEventListener("input", function() {
            if (valeurElementInput.value === currentValue) {
                valeurElementInput.value = fieldInput.value;
            }
        });
    });
    </script>
</body>
</html>