<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';

$database = new Database();
$db = $database->getConnection();

$est_connecte = false;
$nom_client = "";

if (isset($_SESSION['client_id'])) {
    $est_connecte = true;
    $current_client_id = $_SESSION['client_id'];
    $nom_client = $_SESSION['client_nom'];
} else {
    $current_client_id = null;
}

$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

$restaurant = new Restaurant($db);

$categories = $restaurant->getAllCategories();

$current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lon = isset($_GET['lon']) ? $_GET['lon'] : null;

$stmt_cat = null;
$current_cat_info = null;

if ($lat && $lon) {
    $stmt = $restaurant->getRestaurantsAround($lat, $lon, 3); // Rayon de 3km
    $current_cat = null;
    $titre_special = "Restaurants autour de vous (3km) 📍";

} elseif (isset($current_cat)) {
    $stmt_open = $restaurant->getOpenedByCategory($current_cat);
    $stmt_close = $restaurant->getClosedByCategory($current_cat);
    
    $cat = $restaurant->getCategoriesById($current_cat);

} else {
    $stmt_open = $restaurant->getAllOpenedRestaurants();
    $stmt_close = $restaurant->getAllClosedRestaurants();
}

include 'views/liste_restaurants.php';
?>