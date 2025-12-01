<?php

// A COPIER TOUT EN HAUT DE login_restaurateur.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './config/Database.php';
require_once './models/Restaurants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = (new Database())->getConnection();
    $restaurant = new Restaurant($db);
    
    $resto = $restaurant->login($_POST['email'], $_POST['mot_de_passe']);
    
    if ($resto) {
        $_SESSION['restaurant_id'] = $resto['restaurant_id'];
        $_SESSION['restaurant_nom'] = $resto['nom'];
        header("Location: espace_restaurateur.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
include 'views/restaurateur_login.php'; 
?>