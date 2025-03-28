<?php
session_start();

// Vérification du token CSRF
if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['csrf_users_add']){
    die('<p>CSRF invalide</p>');
}

// Supprime le token en session pour qu'il soit regénéré
unset($_SESSION['csrf_users_add']);

// Initialisation du tableau d'erreurs
$errors = [];

// Validation du nom
if (isset($_POST["nom"]) && !empty($_POST["nom"])){
    $nom = htmlspecialchars($_POST["nom"]);
} else {
    $errors[] = "Le nom de l'école est obligatoire";
}

// Validation de l'adresse
if (isset($_POST["adresse"]) && !empty($_POST["adresse"])){
    $adresse = htmlspecialchars($_POST["adresse"]);
} else {
    $errors[] = "L'adresse est obligatoire";
}

// Validation de l'identifiant
if (isset($_POST["identifiant"]) && !empty($_POST["identifiant"])){
    $identifiant = htmlspecialchars($_POST["identifiant"]);
} else {
    $errors[] = "L'identifiant est obligatoire";
}

// Validation du mot de passe
if (isset($_POST["mdp"]) && !empty($_POST["mdp"])){
    $mdp = htmlspecialchars($_POST["mdp"]);
} else {
    $errors[] = "Le mot de passe est obligatoire";
}

// Validation du mot de passe
if (isset($_POST["role_profil"]) && !empty($_POST["role_profil"])){
    $role_profil = htmlspecialchars($_POST["role_profil"]);
} else {
    $errors[] = "Le role_profil est obligatoire";
}

// Si pas d'erreurs, on procède à l'insertion
if (empty($errors)){
    // users à la base de données
    require_once '../../bdd.php';

    try {
        // Transaction pour assurer la cohérence
        $connexion->beginTransaction();
        
        $check = $connexion->prepare("SELECT identifiant FROM users WHERE identifiant = :identifiant");
        $check->execute(["identifiant" => $identifiant]);
        
        if ($check->rowCount() > 0) {
            // L'identifiant existe déjà
            $errors[] = "Cet identifiant existe déjà dans la base de données";
            throw new Exception("Identifiant déjà existant");
        }
        
        // Insérer une seule fois avec tous les champs
        $sauvegarde = $connexion->prepare("INSERT INTO users (nom, adresse, identifiant, mdp, role_profil)
                                         VALUES (:nom, :adresse, :identifiant, :mdp, :role_profil)");
        
        $sauvegarde->execute([
            "nom" => $nom, 
            "adresse" => $adresse,
            "identifiant" => $identifiant, 
            "mdp" => $mdp,
            "role_profil" => $role_profil
        ]);
        
        if ($sauvegarde->rowCount() > 0) {
            // Tout s'est bien passé, on peut valider la transaction
            $connexion->commit();
            
            header('Location: ../../Mairie/Users/HTML_Users.php');
            exit();
        } else {
            throw new Exception("Échec de l'insertion dans users");
        }
        
    } catch (Exception $e) {
        // En cas d'erreur, on annule toutes les opérations
        $connexion->rollBack();
        $errors[] = "Erreur: " . $e->getMessage();
    }
}

// Affichage des erreurs s'il y en a
if (!empty($errors)) {
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; margin: 20px 0; border-radius: 5px;">';
    echo '<h3>Erreurs détectées :</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '<p><a href="javascript:history.back()">Retourner au formulaire</a></p>';
    echo '</div>';
}
?>