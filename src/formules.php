<?php

//formules.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $restaurant_id = $_GET['id'];
}
else {
    die("Erreur :  ID du restaurant non valide ou manquant.");
}


$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

$info_formules = $restaurant->getFormules($restaurant_id);

include 'views/formules_restaurant.php';

?>