<?php
/*
Résumé :
    - Gestion de l'affichage adaptatif (Header) : Vérifie les variables de session pour afficher soit les boutons de connexion/inscription, soit le profil utilisateur avec les options spécifiques (Panier, Historique, Admin).
    - Logique d'affichage des restaurants (Priorisation) :
        1. Si coordonnées GPS (lat/lon) : Affiche une liste unique triée par distance (via stmt).
        2. Sinon (Affichage standard/Catégorie) : Sépare les résultats en deux listes distinctes, "Ouvert maintenant" (stmt_open) et "Fermé" (stmt_close), pour appliquer des styles visuels différents (opacité, badges).
    - Rendu visuel des cartes : 
        - Génération dynamique des étoiles de notation via une boucle.
        - Formatage conditionnel de la distance (si disponible).
        - Gestion des droits d'interaction : Le bouton "Laisser un avis" (crayon) n'est affiché que si l'utilisateur est connecté.
    - Interactivité Client (JavaScript) :
        - Géolocalisation : Utilise l'API navigateur pour obtenir la position et rediriger vers le contrôleur avec les paramètres GET.
        - Modale d'avis : Gère l'ouverture/fermeture de la fenêtre flottante et injecte l'ID du restaurant ciblé dans le formulaire caché avant l'envoi.
*/
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';

$database = new Database();
$db = $database->getConnection();

$est_connecte = false;
$nom_client = "";

if (isset($_SESSION['client_id'])) {
    $est_connecte = true;
    $current_client_id = $_SESSION['client_id'];
    $nom_client = $_SESSION['client_nom'];
} else {
    $current_client_id = null;
}

$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

$restaurant = new Restaurant($db);

$categories = $restaurant->getAllCategories();

$current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lon = isset($_GET['lon']) ? $_GET['lon'] : null;

$stmt_cat = null;
$current_cat_info = null;

if ($lat && $lon) {
    $stmt = $restaurant->getRestaurantsAround($lat, $lon, 2);
    $current_cat = null;
    $titre_special = "Restaurants autour de vous (2km) 📍";

} elseif (isset($current_cat)) {
    $stmt_open = $restaurant->getOpenedByCategory($current_cat);
    $stmt_close = $restaurant->getClosedByCategory($current_cat);

    $cat = $restaurant->getCategoriesById($current_cat);

} else {
    $stmt_open = $restaurant->getAllOpenedRestaurants();
    $stmt_close = $restaurant->getAllClosedRestaurants();
}


include 'views/liste_restaurants.php';
?>