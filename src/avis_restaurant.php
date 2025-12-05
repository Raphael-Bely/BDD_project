<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Fidelite.php';
require_once './models/Restaurants.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$resto_id = $_GET['id'];
$db = (new Database())->getConnection();

// info resto
$resto = new Restaurant($db);
$restoInfos = $resto->getByID($resto_id);

// tableau avis
$comModel = new Fidelite($db);
$avis_list = $comModel->getAvisByRestaurant($resto_id);

include 'views/avis_resto.php';
?>
