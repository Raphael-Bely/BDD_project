<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    $commande_id = $_POST['commande_id'];
    $database = new Database();
    $db = $database->getConnection();

    $commande = new Commande($db);

    if ($commande->confirmOrder($commande_id)) {
        header("Location: commande.php");
        exit();
    } else {
        echo "Erreur lors de la confirmation de la commande.";
    }
}

?>