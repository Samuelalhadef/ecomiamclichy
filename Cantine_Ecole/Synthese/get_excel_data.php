<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['identifiant'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non authentifié']);
    exit;
}

$identifiant = $_SESSION['identifiant'];

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=beta", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les données de synthèse de l'utilisateur connecté
    $query = "
    SELECT 
        u.identifiant,
        m.date_menu,
        m.valeur_element,
        p.nb_repasprevus,
        p.nb_repasconsommes,
        p.nb_repasconsommesadultes,
        p.pesee_restes,
        p.pesee_pain,
        p.moyenne_reste_enfant,
        v.grande_faim,
        v.petite_faim,
        v.aime,
        v.aime_moyen,
        v.aime_pas
    FROM 
        menu m
    LEFT JOIN 
        pesee p ON m.date_menu = p.date_menu
    LEFT JOIN 
        vote v ON m.date_menu = v.date_menu AND v.identifiant = :identifiant
    LEFT JOIN 
        users u ON v.identifiant = u.identifiant
    WHERE 
        u.identifiant = :identifiant
    ORDER BY 
        m.date_menu DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
    $stmt->execute();

    // Récupérer les résultats
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si des résultats existent
    if (!$resultats) {
        echo json_encode(['message' => 'Aucune donnée trouvée pour cet utilisateur.']);
        exit;
    }

    // Préparer les données pour l'export
    $data = [];

    // Ajouter les en-têtes
    $data[] = [
        'Identifiant',
        'Date Menu',
        'Élément voté',
        'Repas Prévus',
        'Repas Consommés',
        'Repas Consommés Adultes',
        'Restes (kg)',
        'Restes Pain (kg)',
        'Moyenne Reste enfant (g)',
        'Grande Faim',
        'Petite Faim',
        'Aime',
        'Aime Moyen',
        'N\'Aime Pas'
    ];

    // Ajouter les données
    foreach ($resultats as $ligne) {
        $data[] = [
            $ligne['identifiant'] ?? 'N/A',
            $ligne['date_menu'] ?? 'N/A',
            $ligne['valeur_element'] ?? 'N/A',
            $ligne['nb_repasprevus'] ?? 'N/A',
            $ligne['nb_repasconsommes'] ?? 'N/A',
            $ligne['nb_repasconsommesadultes'] ?? 'N/A',
            $ligne['pesee_restes'] ?? 'N/A',
            $ligne['pesee_pain'] ?? 'N/A',
            $ligne['moyenne_reste_enfant'] ?? 'N/A',
            $ligne['grande_faim'] ?? 'N/A',
            $ligne['petite_faim'] ?? 'N/A',
            $ligne['aime'] ?? 'N/A',
            $ligne['aime_moyen'] ?? 'N/A',
            $ligne['aime_pas'] ?? 'N/A'
        ];
    }

    // Envoyer les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($data);

} catch(PDOException $e) {
    // Gestion des erreurs
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

