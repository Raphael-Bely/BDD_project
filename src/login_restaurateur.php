<?php
/*
Résumé :
    - Initialisation : Configuration de l'affichage des erreurs, démarrage de la session et chargement des dépendances (Database, Modèle Restaurant).
    - Traitement de la requête (POST) :
        - Connexion à la base de données et instanciation du modèle `Restaurant`.
        - Appel de la méthode `login` du modèle avec l'email et le mot de passe récupérés du formulaire.
    - Scénario de Succès :
        - Si le modèle retourne les infos du restaurant, enregistrement de l'ID (`restaurant_id`) et du nom (`restaurant_nom`) dans la session.
        - Redirection immédiate vers le tableau de bord (`restaurateur_space.php`).
    - Scénario d'Échec :
        - Si la connexion échoue, définition d'un message d'erreur (`$error`).
        - Le script continue pour inclure la vue, qui affichera ce message au-dessus du formulaire.
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './config/Database.php';
require_once './models/Restaurants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = (new Database())->getConnection();
    $restaurant = new Restaurant($db);
    
    $resto = $restaurant->login($_POST['email'], $_POST['mot_de_passe']);
    
    if ($resto) {
        $_SESSION['restaurant_id'] = $resto['restaurant_id'];
        $_SESSION['restaurant_nom'] = $resto['nom'];
        header("Location: restaurateur_space.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
include 'views/restaurateur_login.php'; 
?>