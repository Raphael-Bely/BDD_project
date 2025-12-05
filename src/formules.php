<?php
/*
Résumé :
    - Validation des entrées : Vérifie si le paramètre 'id' est présent dans l'URL et s'il est numérique. Arrête l'exécution avec un message d'erreur si ce n'est pas le cas.
    - Connexion BDD : Initialise la connexion et instancie le modèle `Restaurant`.
    - Récupération des données : Appelle la méthode `getFormules` du modèle pour récupérer toutes les formules du restaurant ainsi que les catégories d'items qui les composent (via une jointure SQL).
    - Rendu de la vue : Inclut le fichier `views/formules_restaurant.php`.
    - Structuration des données (dans la Vue) : La vue effectue un traitement sur les données brutes (qui arrivent souvent sous forme d'une ligne par catégorie pour une même formule). Elle boucle sur les résultats pour les regrouper dans un tableau structuré (`$formules_organisees`), fusionnant les catégories sous l'identifiant unique de la formule avant l'affichage.
*/

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $restaurant_id = $_GET['id'];
}
else {
    die("Erreur :  ID du restaurant non valide ou manquant.");
}


$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

$info_formules = $restaurant->getFormules($restaurant_id);

include 'views/formules_restaurant.php';

?>