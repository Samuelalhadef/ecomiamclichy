<?php
try {
    $connexion = new PDO("mysql:host=127.0.0.1; dbname=beta", "root", "");
}
catch (Exception $e){
    die("Erreur SQL :" . $e->getMessage());
}

?>
