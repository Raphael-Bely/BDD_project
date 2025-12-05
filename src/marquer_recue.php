<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Clients.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['commande_id'])) {
    header("Location: suivi.php");
    exit();
}

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$commande_id = $_POST['commande_id'];
$client_id = $_SESSION['client_id'];


$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);
$client = new Client($db);

if ($commande->isOrderOwnedByClient($commande_id, $client_id)) {
    
    if ($commande->marquerCommeRecue($commande_id)) {
        
        if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true) {
            
            $nb_commandes_restantes = $commande->countOrdersEnCours($client_id);

            if ($nb_commandes_restantes == 0) {
                
                $client->deleteGuestAccount($client_id);

                session_destroy();
                header("Location: index.php?msg=guest_cleanup");
                exit();
            }
        }
        
        header("Location: suivi.php");
        exit();
    }
}

header("Location: suivi.php");
exit();
?>