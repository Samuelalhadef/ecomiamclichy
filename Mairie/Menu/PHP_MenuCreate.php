<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['csrf_menu_add']) {
    die('<p>CSRF invalide</p>');
}

unset($_SESSION['csrf_menu_add']);

$champs = ["date_menu", "nom_menu", "entree", "plat", "garniture", "produit_laitier", "dessert", "divers"];
$donnees = [];
$erreurs = [];

foreach ($champs as $champ) {
    if (!empty($_POST[$champ])) {
        $donnees[$champ] = htmlspecialchars($_POST[$champ]);
    } else {
        $erreurs[] = "<p>Le champ $champ est obligatoire</p>";
    }
}

// Récupérer la valeur de l'élément voté
$valeur_element = !empty($_POST["valeur_element"]) ? htmlspecialchars($_POST["valeur_element"]) : "";

if (empty($valeur_element)) {
    $erreurs[] = "<p>Veuillez sélectionner une valeur valide.</p>";
}

if (!empty($erreurs)) {
    foreach ($erreurs as $erreur) {
        echo $erreur;
    }
    exit();
}

require_once '../../bdd.php';

$sauvegarde = $connexion->prepare("INSERT INTO menu (date_menu, nom_menu, entree, plat, garniture, produit_laitier, dessert, divers, valeur_element)
                                   VALUES (:date_menu, :nom_menu, :entree, :plat, :garniture, :produit_laitier, :dessert, :divers, :valeur_element)");

$sauvegarde->execute($donnees + ["valeur_element" => $valeur_element]);

// Vérifier si l'élément a bien été ajouté pour aujourd'hui
$dateAujourdHui = date('Y-m-d');
$sql = "SELECT * FROM menu WHERE date_menu = :date_aujourdhui AND valeur_element IS NOT NULL";
$stmt = $connexion->prepare($sql);
$stmt->execute(['date_aujourdhui' => $aujourdHui]);
$menuVote = $stmt->fetch(PDO::FETCH_ASSOC);

// Si un menu a été voté aujourd'hui, stocker l'information dans une session
if ($menuVote) {
    $_SESSION['menu_voté'] = $menuVote['id']; // Stocke l'ID du menu voté
}

// Redirection après insertion
header('Location: ../../Mairie/Menu/HTML_Liste_Menu.php');
exit();
