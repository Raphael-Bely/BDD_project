<?php

/*
Résumé :
    - Vérification d'accès : Contrôle si un client est connecté via la session. Si non, redirection vers la page de login.
    - Récupération des commandes actives : Appel au modèle `Commande` pour récupérer uniquement les commandes "en cours" (ni terminées, ni annulées) associées au client connecté.
    - Affichage conditionnel (Mode Invité) : Si l'utilisateur est un invité (`is_guest`), un bandeau d'information spécifique est affiché pour l'avertir que la validation de réception entraînera la suppression de sa session temporaire.
    - Gestion des états :
        - Les commandes sont affichées avec un statut visuel "En cours de préparation" (logique simplifiée pour l'instant).
        - Chaque carte de commande propose deux actions : "Détails" (lien vers la vue détaillée) et "J'ai reçu ma commande" (formulaire POST).
    - Validation de réception : Le bouton "Reçu" déclenche une action critique (`marquer_recue.php`) qui met à jour le statut de la commande en base de données (passant de 'en cours' à 'achevé').
*/

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

$database = new Database();
$db = $database->getConnection();

$commande = new Commande($db);

$stmt = $commande->getCommandesEnCours($client_id);

include 'views/suivi_commandes.php';
?>