<?php
/*
Résumé :
    - Validation des entrées : Vérifie que le paramètre 'item_id' est présent dans l'URL et qu'il est bien numérique. Stoppe l'exécution si ce n'est pas le cas.
    - Connexion BDD : Initialise la connexion et instancie le modèle `Ingredient`.
    - Récupération des données : Appelle la méthode `getIngredientsByItem($item_id)` du modèle pour récupérer les ingrédients associés à ce plat spécifique.
    - Rendu de la vue : Inclut le fichier `views/liste_ingredients.php` pour afficher les données sous forme de tableau nutritionnel.
*/
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Ingredients.php';

if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
}
else {
    die("Erreur : ID de l'item non valide ou manquant.");
}

$database = new Database();
$db = $database->getConnection();

$ingredient = new Ingredient($db);

$stmt = $ingredient->getIngredientsByItem($item_id);

include 'views/liste_ingredients.php';

?>