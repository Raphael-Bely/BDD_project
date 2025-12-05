<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Plats.php';
require_once './models/Restaurants.php';
require_once './models/Commandes.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $restaurant_id = $_GET['id'];
} else {
    die("Erreur :  ID du restaurant non valide ou manquant.");
}

$database = new Database();
$db = $database->getConnection();
$plat = new Plat($db);
$restaurant = new Restaurant($db);
$commandeModel = new Commande($db);

$restaurant_info = $restaurant->getByID($restaurant_id);
$stmt_plats = $plat->getMenuByRestaurant($restaurant_id);

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];

    $stmt_commande_by_restau = $restaurant->getCurrentCommandeFromRestaurant($client_id, $restaurant_id);

    // Map des items déjà dans le panier pour ce restaurant (item_id => quantite)
    $items_in_cart = [];
    if ($stmt_commande_by_restau) {
        $ma_commande_temp = $stmt_commande_by_restau->fetch(PDO::FETCH_ASSOC);
        if ($ma_commande_temp) {
            $stmt_items_in_order = $commandeModel->afficherItemCommande($ma_commande_temp['commande_id']);
            $items_raw = $stmt_items_in_order->fetchAll(PDO::FETCH_ASSOC);
            foreach ($items_raw as $row) {
                $items_in_cart[$row['item_id']] = $row['quantite'];
            }
            // On remet le pointeur sur la commande pour l'utilisation dans la vue
            $stmt_commande_by_restau = $restaurant->getCurrentCommandeFromRestaurant($client_id, $restaurant_id);
        }
    }

} else {
    $client_id = null;
    $stmt_commande_by_restau = null;
    $items_in_cart = [];
}


include 'views/menu_restaurant.php';

?>