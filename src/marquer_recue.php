<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Clients.php'; // On a besoin du modèle Client pour la suppression

// Vérification de base
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

// Connexions
$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);
$client = new Client($db);

// 1. VÉRIFICATION SÉCURITÉ (Via le Modèle)
if ($commande->isOrderOwnedByClient($commande_id, $client_id)) {
    
    // 2. ACTION PRINCIPALE : Marquer comme reçue
    if ($commande->marquerCommeRecue($commande_id)) {
        
        // 3. LOGIQUE SPÉCIALE INVITÉ
        if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true) {
            
            // On demande au modèle s'il reste des commandes actives
            $nb_commandes_restantes = $commande->countOrdersEnCours($client_id);

            // S'il n'y a plus de commandes en cours, on supprime le compte temporaire
            if ($nb_commandes_restantes == 0) {
                
                // Appel au modèle Client pour la suppression
                $client->deleteGuestAccount($client_id);

                // Déconnexion et retour accueil
                session_destroy();
                header("Location: index.php?msg=guest_cleanup");
                exit();
            }
        }
        
        // Cas standard (Client normal ou Invité avec d'autres commandes)
        header("Location: suivi.php");
        exit();
    }
}

// Si on arrive ici (échec vérif ou échec update), retour au suivi
header("Location: suivi.php");
exit();
?>