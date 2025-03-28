<?php
session_start();
require_once '../../bdd.php';

// Vérification du token CSRF
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_users_update']) {
    die('<p>CSRF invalide</p>');
}
unset($_SESSION['csrf_users_update']); // Supprime le token après utilisation

// Vérification des champs obligatoires
if (
    !isset($_POST['id_users']) || empty($_POST['id_users']) || 
    !isset($_POST['nom']) || empty($_POST['nom']) ||
    !isset($_POST['adresse']) || empty($_POST['adresse']) ||
    !isset($_POST['identifiant']) || empty($_POST['identifiant']) ||
    !isset($_POST['role_profil']) || empty($_POST['role_profil'])
) {
    die('<p>Données incomplètes</p>');
}

// Sécurisation des données
$id_users = intval($_POST['id_users']);
$nom = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
$adresse = htmlspecialchars(trim($_POST['adresse']), ENT_QUOTES, 'UTF-8');
$identifiant = htmlspecialchars(trim($_POST['identifiant']), ENT_QUOTES, 'UTF-8');
$role_profil = htmlspecialchars(trim($_POST['role_profil']), ENT_QUOTES, 'UTF-8');

$params = [
    'nom' => $nom,
    'adresse' => $adresse,
    'identifiant' => $identifiant,
    'role_profil' => $role_profil,
    'id' => $id_users
];

// Vérification et gestion du mot de passe
if (!empty($_POST['mdp'])) {
    $mdp = trim($_POST['mdp']); // Mot de passe non haché pour la concordance des clés étrangères
    $mdp_hashed = password_hash($mdp, PASSWORD_BCRYPT);
    
    // Vérifier si l'identifiant existe déjà dans `users`
    $sql_check = "SELECT COUNT(*) FROM users WHERE identifiant = :identifiant";
    $check = $connexion->prepare($sql_check);
    $check->execute(['identifiant' => $identifiant]);
    
    if ($check->fetchColumn() == 0) {
        // Insérer l'identifiant et le mot de passe non haché dans users
        $sql_insert = "INSERT INTO users (identifiant, mdp, nom, adresse, role_profil) 
               VALUES (:identifiant, :mdp, :nom, :adresse, :role_profil)";
        $insert = $connexion->prepare($sql_insert);
        $insert->execute([
            'identifiant' => $identifiant, 
            'mdp' => $mdp,
            'nom' => $nom,
            'adresse' => $adresse,
            'role_profil' => $role_profil
        ]);
    } else {
        // Mettre à jour le mot de passe dans la table users
        $sql_update_conn = "UPDATE users SET mdp = :mdp WHERE identifiant = :identifiant";
        $update_conn = $connexion->prepare($sql_update_conn);
        $update_conn->execute(['identifiant' => $identifiant, 'mdp' => $mdp]);
    }
    
    // Ajouter le mot de passe à la mise à jour de users
    $sql = "UPDATE users SET 
            nom = :nom, adresse = :adresse,
            identifiant = :identifiant, mdp = :mdp, role_profil = :role_profil
            WHERE id = :id";
    $params['mdp'] = $mdp; // Utiliser le même mot de passe non haché
} else {
    $sql = "UPDATE users SET 
            nom = :nom, adresse = :adresse,
            identifiant = :identifiant, role_profil = :role_profil
            WHERE id = :id";
}

// Exécution de la requête SQL
$update = $connexion->prepare($sql);
if ($update->execute($params)) {
    header("Location: ../../Mairie/Users/HTML_Users.php?id=" . $id_users . "&success=1");
    exit();
} else {
    die('<p>Erreur lors de la mise à jour.</p>');
}
?>
