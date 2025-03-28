<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['identifiant'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Utilisateur non authentifié']));
}

$identifiant = $_SESSION['identifiant'];

// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'beta';
$username = 'root';
$password = '';

try {
    // Établir la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer uniquement les données de l'utilisateur connecté
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
        die(json_encode(['message' => 'Aucune donnée trouvée pour cet utilisateur.']));
    }

    // Créer un nouveau tableur
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // En-têtes du fichier Excel
    $headers = [
        'A' => 'Identifiant',
        'B' => 'Date Menu',
        'C' => 'Élément voté',
        'D' => 'Repas Prévus',
        'E' => 'Repas Consommés',
        'F' => 'Repas Consommés Adultes',
        'G' => 'Restes (kg)',
        'H' => 'Restes Pain (kg)',
        'I' => 'Moyenne Reste Enfant (g)',
        'J' => 'Grande Faim',
        'K' => 'Petite Faim',
        'L' => 'Aime',
        'M' => 'Aime Moyen',
        'N' => 'N\'Aime Pas'
    ];

    // Ajouter les en-têtes
    foreach ($headers as $col => $header) {
        $sheet->setCellValue($col . '1', $header);
    }

    // Ajouter les données
    $rowIndex = 2;
    foreach ($resultats as $ligne) {
        $sheet->setCellValue('A' . $rowIndex, $ligne['identifiant'] ?? 'N/A');
        $sheet->setCellValue('B' . $rowIndex, $ligne['date_menu'] ?? 'N/A');
        $sheet->setCellValue('C' . $rowIndex, $ligne['valeur_element'] ?? 'N/A');
        $sheet->setCellValue('D' . $rowIndex, $ligne['nb_repasprevus'] ?? 'N/A');
        $sheet->setCellValue('E' . $rowIndex, $ligne['nb_repasconsommes'] ?? 'N/A');
        $sheet->setCellValue('F' . $rowIndex, $ligne['nb_repasconsommesadultes'] ?? 'N/A');
        $sheet->setCellValue('G' . $rowIndex, $ligne['pesee_restes'] ?? 'N/A');
        $sheet->setCellValue('H' . $rowIndex, $ligne['pesee_pain'] ?? 'N/A');
        $sheet->setCellValue('I' . $rowIndex, $ligne['moyenne_reste_enfant'] ?? 'N/A');
        $sheet->setCellValue('J' . $rowIndex, $ligne['grande_faim'] ?? 'N/A');
        $sheet->setCellValue('K' . $rowIndex, $ligne['petite_faim'] ?? 'N/A');
        $sheet->setCellValue('L' . $rowIndex, $ligne['aime'] ?? 'N/A');
        $sheet->setCellValue('M' . $rowIndex, $ligne['aime_moyen'] ?? 'N/A');
        $sheet->setCellValue('N' . $rowIndex, $ligne['aime_pas'] ?? 'N/A');
        
        $rowIndex++;
    }

    // Définir les en-têtes pour le téléchargement
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="synthese_donnees_user.xlsx"');
    header('Cache-Control: max-age=0');

    // Créer le fichier Excel et l'envoyer au téléchargement
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch(PDOException $e) {
    // Gestion des erreurs
    die(json_encode(['error' => $e->getMessage()]));
}
?>
