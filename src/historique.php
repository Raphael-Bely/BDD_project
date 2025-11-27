<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Initialiser la connexion à la BDD
$database = new Database();
$db = $database->getConnection();

// Créer une instance du modèle Commande
$commande = new Commande($db);

// Récupérer l'historique des commandes
$stmt = $commande->getHistoriqueCommandes($client_id);

// Inclure la vue pour afficher l'historique
include 'views/historique_commandes.php';
?>