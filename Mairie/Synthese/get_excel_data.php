<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'beta';
$username = 'root';
$password = '';

try {
    // Établir la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les données de synthèse
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
        vote v ON m.date_menu = v.date_menu
    LEFT JOIN 
        users u ON v.identifiant = u.identifiant
    ORDER BY 
        u.identifiant ASC,
        m.date_menu DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Récupérer les résultats
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

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