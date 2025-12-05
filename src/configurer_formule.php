<?php
/*
Résumé :
    - Gestion de Session (Pattern Wizard) : Utilise une variable de session (`$_SESSION['formule']`) pour persister l'état de la configuration entre les différentes pages, car le protocole HTTP est sans état.
    - Initialisation : Au premier appel (action=init), récupère la structure de la formule (quelles catégories, dans quel ordre) depuis la BDD via le modèle Formule et initialise le compteur d'étapes à 0.
    - Traitement d'étape : À chaque soumission POST, sauvegarde l'item choisi dans la session et incrémente le compteur d'étape.
    - Vérification de complétude : 
        - Si toutes les étapes sont validées (compteur >= nombre d'étapes), appelle le modèle Commande (`addFullFormuleToOrder`) pour sauvegarder la formule complète et ses composants en une seule transaction.
        - Nettoie la session et redirige vers le menu.
    - Préparation de l'affichage : Si la configuration continue, identifie la catégorie requise pour l'étape actuelle et récupère via le modèle Plat tous les items disponibles pour cette catégorie spécifique dans ce restaurant.
*/

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Formules.php'; 
require_once './models/Plats.php';    
require_once './models/Commandes.php';

$db = (new Database())->getConnection();

if (isset($_GET['action']) && $_GET['action'] === 'init') {
    $formule = new Formule($db);
    $etapes = $formule->getComposition($_GET['formule_id']); 

    $_SESSION['formule'] = [
        'formule_id' => $_GET['formule_id'],
        'restaurant_id' => $_GET['restaurant_id'],
        'etapes' => $etapes,           
        'current_step' => 0,           
        'choix_items' => []            
    ];
    header("Location: configurer_formule.php");
    exit();
}

if (!isset($_SESSION['formule'])) { header("Location: index.php"); exit(); }

$formule_info = &$_SESSION['formule']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    
    $formule_info['choix_items'][] = $_POST['item_id']; 
    $formule_info['current_step']++; 

    if ($formule_info['current_step'] >= count($formule_info['etapes'])) {
        
        $commande = new Commande($db);
        
        $commande->addFullFormuleToOrder(
            $_SESSION['client_id'], 
            $formule_info['restaurant_id'], 
            $formule_info['formule_id'], 
            $formule_info['choix_items'] 
        );

        unset($_SESSION['formule']);
        header("Location: menu.php?id=" . $formule_info['restaurant_id']);
        exit();
    }
    
    header("Location: configurer_formule.php");
    exit();
}

$current_categorie = $formule_info['etapes'][$formule_info['current_step']]; 
$itemModel = new Plat($db);
$items_disponibles = $itemModel->getItemsDisponibles($formule_info['restaurant_id'], $current_categorie['categorie_item_id']);

include 'views/selecteur_item_formule.php';
?>