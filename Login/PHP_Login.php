<?php
session_start();

// Vérification du token CSRF
if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['csrf_users']) {
    die('<p>CSRF invalide</p>');
}

// Supprime le token en session pour qu'il soit regénéré
unset($_SESSION['csrf_users']);

// Connexion à la base de données
require_once '../bdd.php';

$errors = [];

// Vérification que l'identifiant est bien fourni
if (!isset($_POST["identifiant"]) || empty($_POST["identifiant"])) {
    $errors[] = "L'identifiant est obligatoire";
}

// Vérification que le mot de passe est bien fourni
if (!isset($_POST["mdp"]) || empty($_POST["mdp"])) {
    $errors[] = "Le mot de passe est obligatoire";
}

// Si des erreurs sont présentes, on les affiche et on arrête
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>" . $error . "</p>";
    }
    exit();
}

// Récupération de l'utilisateur correspondant à l'identifiant
$sql = "SELECT identifiant, mdp, role_profil FROM users WHERE identifiant = :identifiant LIMIT 1";

try {
    $req = $connexion->prepare($sql);
    $req->execute(['identifiant' => $_POST["identifiant"]]);
    
    if ($user = $req->fetch()) {
        
        $identifiant = $user['identifiant'];
        $mdp_hache = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
        $role_profil = $user['role_profil'];
        
        // Test avec password_verify
        $verify_result = password_verify($_POST["mdp"], $mdp_hache);
        
        // Test avec comparaison directe
        $direct_match = ($_POST["mdp"] == $mdp_hache);
        
        // Essayez les deux méthodes
        if ($verify_result || $direct_match) {
            
            // Authentification réussie
            $_SESSION['role_profil'] = $role_profil;
            $_SESSION['identifiant'] = $identifiant;

            if ($role_profil == 'Admin') {
                header('Location: ../../Mairie/HTML_Admin_Home.php');
            } else if($role_profil == 'User'){
                header('Location: ../../Cantine_Ecole/HTML_User_Home.php');
            }
            exit();

        }
        
        else {
            // Mot de passe incorrect
            echo "<p>L'identifiant ou le mot de passe ne correspondent pas</p>";
        }
    } else {
        // Aucun utilisateur trouvé avec cet identifiant
        echo "<p>Aucun utilisateur trouvé avec l'identifiant: " . $_POST["identifiant"] . "</p>";
    }
} catch (Exception $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>";
}
?>

