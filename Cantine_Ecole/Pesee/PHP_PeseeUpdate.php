<?php
session_start();
require_once "../../bdd.php";

if (!isset($_SESSION['identifiant'])) {
    die("Erreur : Utilisateur non connecté");
}

// Vérification du token CSRF
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_pesee_add']) {
    die("Erreur : Token CSRF invalide");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Date du jour
        $dateAujourdhui = date('Y-m-d');
        $identifiant = $_SESSION['identifiant'];

        // Récupérer ou créer l'entrée de pesée pour aujourd'hui
        $requeteExistante = $connexion->prepare("SELECT id FROM pesee WHERE date_menu = :date_menu AND identifiant = :identifiant ORDER BY id DESC LIMIT 1");
        $requeteExistante->execute([
            ':date_menu' => $dateAujourdhui, 
            ':identifiant' => $identifiant
        ]);
        $peseeExistante = $requeteExistante->fetch(PDO::FETCH_ASSOC);

        // Préparer les données du formulaire
        $moyenne_reste_enfant = intval($_POST['moyenne_reste_enfant']);
        $nb_repasprevus = intval($_POST['nb_repasprevus']);
        $nb_repasconsommes = intval($_POST['nb_repasconsommes']);
        $nb_repasconsommesadultes = intval($_POST['nb_repasconsommesadultes']);
        $pesee_restes = floatval($_POST['pesee_restes']);
        $pesee_pain = floatval($_POST['pesee_pain']);

        if ($peseeExistante) {
            // Mise à jour de l'entrée existante
            $requete = $connexion->prepare("UPDATE pesee SET
                moyenne_reste_enfant = :moyenne_reste_enfant,
                nb_repasprevus = :nb_repasprevus,
                nb_repasconsommes = :nb_repasconsommes,
                nb_repasconsommesadultes = :nb_repasconsommesadultes,
                pesee_restes = :pesee_restes,
                pesee_pain = :pesee_pain
                WHERE id = :id");
            
            $requete->execute([
                ':moyenne_reste_enfant' => $moyenne_reste_enfant,
                ':nb_repasprevus' => $nb_repasprevus,
                ':nb_repasconsommes' => $nb_repasconsommes,
                ':nb_repasconsommesadultes' => $nb_repasconsommesadultes,
                ':pesee_restes' => $pesee_restes,
                ':pesee_pain' => $pesee_pain,
                ':id' => $peseeExistante['id']
            ]);
        } else {
            // Insertion d'une nouvelle entrée
            $requete = $connexion->prepare("INSERT INTO pesee (
                identifiant, 
                date_menu,
                nb_repasprevus, 
                nb_repasconsommes, 
                nb_repasconsommesadultes, 
                pesee_restes, 
                pesee_pain,
                moyenne_reste_enfant
            ) VALUES (
                :identifiant, 
                :date_menu, 
                :nb_repasprevus, 
                :nb_repasconsommes, 
                :nb_repasconsommesadultes, 
                :pesee_restes, 
                :pesee_pain,
                :moyenne_reste_enfant
            )");
            
            $requete->execute([
                ':identifiant' => $identifiant,
                ':date_menu' => $dateAujourdhui,
                ':nb_repasprevus' => $nb_repasprevus,
                ':nb_repasconsommes' => $nb_repasconsommes,
                ':nb_repasconsommesadultes' => $nb_repasconsommesadultes,
                ':pesee_restes' => $pesee_restes,
                ':pesee_pain' => $pesee_pain,
                ':moyenne_reste_enfant' => $moyenne_reste_enfant
            ]);
        }

        // Redirection avec succès
        header("Location: ../../Cantine_Ecole/Pesee/HTML_Interface_Pesee.php?success=1");
        exit();

    } catch (PDOException $e) {
        // Gestion des erreurs
        error_log("Erreur de mise à jour de la pesée : " . $e->getMessage());
        header("Location: ../../Cantine_Ecole/Pesee/HTML_Interface_Pesee.php?error=1");
        exit();
    }
} else {
    // Accès direct non autorisé
    header("Location: ../../Cantine_Ecole/Pesee/HTML_Interface_Pesee.php");
    exit();
}
?>
