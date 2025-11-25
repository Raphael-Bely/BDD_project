<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurant_id'], $_POST['item_id'], $_SESSION['client_id'])) {
    
    $db = (new Database())->getConnection();
    $commande = new Commande($db);

    $succes = $commande->ajouterAuPanier(
        $_SESSION['client_id'], 
        $_POST['restaurant_id'], 
        $_POST['item_id']
    );

    if ($succes) {
        header("Location: menu.php?id=" . $_POST['restaurant_id']);
        exit();
    } else {
        die("Erreur lors de l'ajout au panier.");
    }

} else {
    header("Location: index.php");
    exit();
}
?>