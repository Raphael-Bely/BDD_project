<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Inclure les fichiers nécessaires
require_once './config/Database.php';
require_once './models/Commandes.php';

// 2. Initialiser la connexion à la BDD
$database = new Database();
$db = $database->getConnection();

$commande_id = $_POST['commande_id'];

// 3. Créer une instance du Modèle Restaurant
$commande = new Commande($db);

$stmt = $commande->afficherItemCommande($commande_id);
// 5. Inclure la Vue pour afficher les données
// Note : Le fichier liste_restaurants.php utilise la variable $stmt
include 'views/derniere_commande.php';
?>

