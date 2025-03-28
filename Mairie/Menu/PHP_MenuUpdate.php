<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['csrf_menu_add']){
    die('<p>CSRF invalide</p>');
}
unset($_SESSION['csrf_menu_add']);

if (!isset($_POST['id_menu']) || empty($_POST['id_menu']) || 
    !isset($_POST['field_name']) || empty($_POST['field_name']) ||
    !isset($_POST['field_value'])) {
    die('<p>Données manquantes pour la modification</p>');
}

$id_menu = intval($_POST['id_menu']);
$field_name = $_POST['field_name'];
$field_value = htmlspecialchars($_POST['field_value']);
$valeur_element = isset($_POST['valeur_element']) ? htmlspecialchars($_POST['valeur_element']) : null;

$allowed_fields = ['entree', 'plat', 'garniture', 'produit_laitier', 'dessert', 'divers', 'date_menu', 'nom_menu'];

if (!in_array($field_name, $allowed_fields)) {
    die('<p>Champ non valide pour la modification</p>');
}

require_once '../../bdd.php';

try {
    $connexion->beginTransaction();

    // Mise à jour du champ modifié
    $sql = "UPDATE menu SET $field_name = :value WHERE id = :id";
    $update = $connexion->prepare($sql);
    $update->execute([
        'value' => $field_value,
        'id' => $id_menu
    ]);

    // Vérifier si on doit aussi modifier `valeur_element`
    if ($valeur_element !== null && $valeur_element !== $field_value) {
        $sqlUpdateValeur = "UPDATE menu SET valeur_element = :valeur WHERE id = :id";
        $updateValeur = $connexion->prepare($sqlUpdateValeur);
        $updateValeur->execute([
            'valeur' => $field_value,
            'id' => $id_menu
        ]);
    }

    $connexion->commit();
    
    header('Location: ../../Mairie/Menu/HTML_menu_read.php?id=' . $id_menu);
    exit();
} catch (Exception $e) {
    $connexion->rollBack();
    die('<p>Erreur lors de la modification : ' . $e->getMessage() . '</p>');
}
?>
