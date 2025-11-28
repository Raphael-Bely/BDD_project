<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Inclure les fichiers nécessaires
require_once './config/Database.php';
require_once './models/Restaurants.php';

// 2. Initialiser la connexion à la BDD
$database = new Database();
$db = $database->getConnection();

// Vérification de la connexion du client
$est_connecte = false;
$nom_client = "";

if (isset($_SESSION['client_id'])) {
    $est_connecte = true;
    $current_client_id = $_SESSION['client_id'];
    $nom_client = $_SESSION['client_nom'];
}
else {
    $current_client_id = null;
}

// 3. Créer une instance du Modèle Restaurant
$restaurant = new Restaurant($db);

$categories = $restaurant->getAllCategories();

$current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;  

if (isset($current_cat)) {
    $stmt = $restaurant->getByCategory($current_cat);
    $stmt_cat = $restaurant->getCategoriesById($current_cat);
} else {
    $stmt = $restaurant->getAllRestaurants();
    $stmt_cat = null;
}



include 'views/liste_restaurants.php';
?>