<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();

try {
    require_once __DIR__ . '/config/Database.php';
    require_once __DIR__ . '/models/Complements.php';

    if (!isset($_GET['item_id']) || empty($_GET['item_id']) || $_GET['item_id'] === 'undefined') {
        echo json_encode([]);
        exit;
    }

    $item_id = intval($_GET['item_id']);

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception("Erreur de connexion BDD");
    }

    $complements = new Complements($conn);
    $stmt = $complements->getComplements($item_id);

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'item_id' => $row['item_id'],
            'nom' => $row['nom'],
            'prix' => $row['prix']
        ];
    }

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>