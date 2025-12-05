<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php'; 

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["commande_id"]) && isset($_POST["item_id"])) {
    
    $database = new Database();
    $db = $database->getConnection();
    $restaurant = new Restaurant($db);

    $commande_id = $_POST["commande_id"];
    $item_id = $_POST["item_id"];
    $restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null;

    $restaurant->suppressItemCommande($commande_id, $item_id);

    if ($restaurant_id) {
        header("Location: menu.php?id=" . $restaurant_id);
    } else {
        header("Location: index.php");
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>