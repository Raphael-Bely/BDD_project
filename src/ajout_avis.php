<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Fidelite.php';

// Vérification de connexion
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = intval($_POST['note']);
    $contenu = trim($_POST['contenu']);
    $restaurant_id = intval($_POST['restaurant_id']);
    $client_id = $_SESSION['client_id'];

    if ($note >= 1 && $note <= 5 && !empty($contenu)) {
        $db = (new Database())->getConnection();
        $fidelite = new Fidelite($db);

        if ($fidelite->ajouterAvis($client_id, $restaurant_id, $note, $contenu)) {
            header("Location: index.php?msg=avis_ok"); 
            exit();
        }
    }
}

// En cas d'erreur
header("Location: index.php?error=avis_fail");
exit();
?>