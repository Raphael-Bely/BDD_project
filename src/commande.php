<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();


require_once './config/Database.php';
require_once './models/Commandes.php';

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
}
else {
    die("Erreur : Aucun ID client fourni.");
}

$database = new Database();
$db = $database->getConnection();
$commandeModel = new Commande($db);

$stmt = $commandeModel->getCurrentCommande($client_id);

$commandeInfo = null;
$stmt_items = null;

// On vérifie si on a trouvé une commande
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_cmd = $row['commande_id'];
        
        $stmt_items = $commandeModel->afficherItemCommande($id_cmd);
        $articles = $stmt_items->fetchAll(PDO::FETCH_ASSOC); // On récupère tous les articles en tableau
        
        $row['liste_articles'] = $articles;
        
        $historiqueCommandes[] = $row;
    }
}

include 'views/derniere_commande.php';
?>