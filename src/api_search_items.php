<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['restaurant_id'])) {
    echo json_encode([]);
    exit();
}

try {
    require_once __DIR__ . '/config/Database.php';
    require_once __DIR__ . '/models/Plats.php';

    $db = (new Database())->getConnection();
    -
    $item = new Plat($db);

    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $resto_id = $_SESSION['restaurant_id'];

    if (strlen($term) >= 2) {
        $results = $item->searchItem($resto_id, $term);
        echo json_encode($results);
    } else {
        echo json_encode([]);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([]); 
}
?>