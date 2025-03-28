<?php
session_start();

// Générer un token CSRF s'il n'existe pas
if (!isset($_SESSION['csrf_pesee_add'])) {
    $_SESSION['csrf_pesee_add'] = bin2hex(random_bytes(32));
}

// Validation des entrées
function validateNumericInput($input, $allowDecimals = true) {
    // Convertir en float/int selon le type d'entrée
    $sanitizedInput = filter_var($input, $allowDecimals ? FILTER_VALIDATE_FLOAT : FILTER_VALIDATE_INT);
    
    // Vérifier que le nombre est valide et non négatif
    return $sanitizedInput !== false && $sanitizedInput >= 0;
}

// Vérifier si les données sont envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF : vérification du token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_pesee_add']) {
        error_log("Erreur CSRF : Token invalide - Date : " . date('Y-m-d H:i:s'));
        die("Erreur CSRF : Token invalide");
    }

    // Validation de l'authentification
    if (!isset($_SESSION['identifiant'])) {
        error_log("Tentative d'insertion sans authentification");
        die("Erreur : Utilisateur non connecté");
    }

    // Récupération et validation des données
    $inputData = [
        'moyenne_reste_enfant' => $_POST['moyenne_reste_enfant'] ?? null,
        'pesee_restes' => $_POST['pesee_restes'] ?? null,
        'pesee_pain' => $_POST['pesee_pain'] ?? null,
        'nb_repasprevus' => $_POST['nb_repasprevus'] ?? null,
        'nb_repasconsommes' => $_POST['nb_repasconsommes'] ?? null,
        'nb_repasconsommesadultes' => $_POST['nb_repasconsommesadultes'] ?? null
    ];

    // Validation de chaque input
    foreach ($inputData as $key => $value) {
        $isDecimal = in_array($key, ['pesee_restes', 'pesee_pain']);
        
        if (!validateNumericInput($value, $isDecimal)) {
            error_log("Valeur invalide pour $key : " . $value);
            die("Erreur : Valeur invalide pour $key");
        }
    }

    // Préparation des données
    $moyenne_reste_enfant = floatval($inputData['moyenne_reste_enfant']);
    $pesee_restes = floatval($inputData['pesee_restes']);
    $pesee_pain = floatval($inputData['pesee_pain']);
    $nb_repasprevus = intval($inputData['nb_repasprevus']);
    $nb_repasconsommes = intval($inputData['nb_repasconsommes']);
    $nb_repasconsommesadultes = intval($inputData['nb_repasconsommesadultes']);

    $identifiant = $_SESSION['identifiant'];
    $dateAujourdhui = date('Y-m-d');

    // Inclusion de la connexion à la base de données
    require_once '../../bdd.php';

    try {
        // Configuration des erreurs PDO
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si une ligne existe déjà pour aujourd'hui
        $checkExistsSql = "SELECT id FROM pesee WHERE date_menu = :date_menu AND identifiant = :identifiant";
        $checkExistsReq = $connexion->prepare($checkExistsSql);
        $checkExistsReq->bindParam(':date_menu', $dateAujourdhui, PDO::PARAM_STR);
        $checkExistsReq->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $checkExistsReq->execute();
        $existingPesee = $checkExistsReq->fetch(PDO::FETCH_ASSOC);

        if ($existingPesee) {
            // Mise à jour de la ligne existante
            $updatePeseeSql = "UPDATE pesee SET 
                moyenne_reste_enfant = :moyenne_reste_enfant,
                pesee_restes = :pesee_restes, 
                pesee_pain = :pesee_pain, 
                nb_repasprevus = :nb_repasprevus, 
                nb_repasconsommes = :nb_repasconsommes, 
                nb_repasconsommesadultes = :nb_repasconsommesadultes 
                WHERE date_menu = :date_menu AND identifiant = :identifiant";

            $updatePeseeReq = $connexion->prepare($updatePeseeSql);
            
            $updatePeseeReq->bindParam(':moyenne_reste_enfant', $pesee_restes, PDO::PARAM_STR);
            $updatePeseeReq->bindParam(':pesee_restes', $pesee_restes, PDO::PARAM_STR);
            $updatePeseeReq->bindParam(':pesee_pain', $pesee_pain, PDO::PARAM_STR);
            $updatePeseeReq->bindParam(':nb_repasprevus', $nb_repasprevus, PDO::PARAM_INT);
            $updatePeseeReq->bindParam(':nb_repasconsommes', $nb_repasconsommes, PDO::PARAM_INT);
            $updatePeseeReq->bindParam(':nb_repasconsommesadultes', $nb_repasconsommesadultes, PDO::PARAM_INT);
            $updatePeseeReq->bindParam(':date_menu', $dateAujourdhui, PDO::PARAM_STR);
            $updatePeseeReq->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);

            if ($updatePeseeReq->execute()) {
                // Régénérer le token CSRF après soumission réussie
                $_SESSION['csrf_pesee_add'] = bin2hex(random_bytes(32));
                
                error_log("Mise à jour pesée réussie - Date : $dateAujourdhui - Utilisateur : $identifiant");
                header("Location: ../../Cantine_Ecole/Pesee/HTML_PeseeUpdate.php?success=1");
                exit();
            } else {
                throw new PDOException("Impossible de mettre à jour la pesée.");
            }
        } else {
            // Insertion d'une nouvelle ligne
            $insertPeseeSql = "INSERT INTO pesee (
                moyenne_reste_enfant, pesee_restes, pesee_pain, nb_repasprevus, 
                nb_repasconsommes, nb_repasconsommesadultes, 
                date_menu, identifiant
            ) VALUES (
                :moyenne_reste_enfant, :pesee_restes, :pesee_pain, :nb_repasprevus, 
                :nb_repasconsommes, :nb_repasconsommesadultes, 
                :date_menu, :identifiant
            )";

            $insertPeseeReq = $connexion->prepare($insertPeseeSql);

            $insertPeseeReq->bindParam(':pesee_restes', $pesee_restes, PDO::PARAM_STR);
            $insertPeseeReq->bindParam(':moyenne_reste_enfant', $pesee_restes, PDO::PARAM_STR);
            $insertPeseeReq->bindParam(':pesee_pain', $pesee_pain, PDO::PARAM_STR);
            $insertPeseeReq->bindParam(':nb_repasprevus', $nb_repasprevus, PDO::PARAM_INT);
            $insertPeseeReq->bindParam(':nb_repasconsommes', $nb_repasconsommes, PDO::PARAM_INT);
            $insertPeseeReq->bindParam(':nb_repasconsommesadultes', $nb_repasconsommesadultes, PDO::PARAM_INT);
            $insertPeseeReq->bindParam(':date_menu', $dateAujourdhui, PDO::PARAM_STR);
            $insertPeseeReq->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);

            if ($insertPeseeReq->execute()) {
                // Régénérer le token CSRF après soumission réussie
                $_SESSION['csrf_pesee_add'] = bin2hex(random_bytes(32));
                
                error_log("Insertion pesée réussie - Date : $dateAujourdhui - Utilisateur : $identifiant");
                header("Location: ../../Cantine_Ecole/Pesee/HTML_Interface_Pesee.php?success=1");
                exit();
            } else {
                throw new PDOException("Impossible d'ajouter la pesée.");
            }
        }

    } catch (PDOException $e) {
        // Log détaillé de l'erreur
        error_log("Erreur de traitement de la pesée : " . $e->getMessage() . 
                  " - Date : " . date('Y-m-d H:i:s') . 
                  " - Utilisateur : " . $identifiant);
        
        // Message d'erreur générique côté utilisateur
        die("Une erreur est survenue lors du traitement. Veuillez réessayer.");
    }
}
?>

