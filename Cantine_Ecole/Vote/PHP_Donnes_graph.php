<?php
// Inclusion du fichier de connexion à la base de données
require_once '../../bdd.php';

// Récupération de la date du jour
$dateAujourdhui = date('Y-m-d');

try {
    // Requête pour récupérer le menu du jour
    $menuSql = "SELECT valeur_element FROM menu WHERE date_menu = :dateAujourdhui";
    $menuReq = $connexion->prepare($menuSql);
    $menuReq->execute(['dateAujourdhui' => $dateAujourdhui]);
    $menuResult = $menuReq->fetch(PDO::FETCH_ASSOC);

    // Requête pour récupérer les totaux de votes pour la date du jour
    $sql = "SELECT 
        SUM(aime) as aime,
        SUM(aime_moyen) as aime_moyen,
        SUM(aime_pas) as aime_pas
    FROM vote 
    WHERE date_menu = :dateAujourdhui";

    $req = $connexion->prepare($sql);
    $req->execute(['dateAujourdhui' => $dateAujourdhui]);

    $resultat = $req->fetch(PDO::FETCH_ASSOC);

    // Calcul des totaux
    $total_aime = (int)$resultat['aime'];
    $total_aime_moyen = (int)$resultat['aime_moyen'];
    $total_aime_pas = (int)$resultat['aime_pas'];

    // Retourne les données au format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'aime' => $total_aime,
        'aime_moyen' => $total_aime_moyen,
        'aime_pas' => $total_aime_pas,
        'element' => $menuResult ? $menuResult['valeur_element'] : 'Aucun élément'
    ]);
} catch(PDOException $e) {
    // Gestion des erreurs
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>