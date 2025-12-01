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

// Vérifier qu'un ID de commande est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: historique.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$commande_id = $_GET['id'];

// Initialiser la connexion à la BDD
$database = new Database();
$db = $database->getConnection();

// Créer une instance du modèle Commande
$commande = new Commande($db);

$commande_info = $commandeModel->getCommandeDetails($commande_id, $client_id);

if (!$commande_info) {
    // Si false, commande n'existe pas ou n'appartient pas au client
    header("Location: historique.php");
    exit();
}

// Récupérer les items de la commande
$stmt_items = $commande->afficherItemCommande($commande_id);
$commande_info['liste_articles'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les formules de la commande
$stmt_formules = $commande->afficherFormulesCommande($commande_id);
$raw_formules = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);

$formules_structurees = [];
if (!empty($raw_formules)) {
    foreach ($raw_formules as $ligne) {
        $id_unique = $ligne['instance_id'] ?? $ligne['id'] ?? 0;
        $nom_form = $ligne['nom_formule'] ?? $ligne['nom'] ?? 'Nom Inconnu';
        $prix_form = $ligne['prix'] ?? 0;
        $nom_item = $ligne['nom_item'] ?? $ligne['nom'] ?? '';

        if ($id_unique === 0)
            continue;

        if (!isset($formules_structurees[$id_unique])) {
            $formules_structurees[$id_unique] = [
                'nom' => $nom_form,
                'prix' => $prix_form,
                'items' => []
            ];
        }

        if (!empty($nom_item)) {
            $formules_structurees[$id_unique]['items'][] = $nom_item;
        }
    }
}

$commande_info['liste_formules'] = $formules_structurees;

// Inclure la vue
include 'views/detail_commande.php';
?>