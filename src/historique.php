<?php
/*
Résumé :
    - Vérification de l'authentification : Vérifie si l'utilisateur est connecté via la session. Redirige vers la page de connexion si ce n'est pas le cas.
    - Identification de l'utilisateur : Récupère le `client_id` stocké en session.
    - Récupération des données : Appelle la méthode `getHistoriqueCommandes` du modèle `Commande` pour récupérer toutes les commandes terminées ou passées associées à cet ID client.
    - Rendu de la vue : Inclut le fichier `views/historique_commandes.php` pour l'affichage.
    - Logique visuelle (dans la Vue) :
        - Itère à travers les commandes récupérées.
        - Transforme les codes d'état bruts (ex: 'acheve', 'en_livraison') en libellés lisibles et applique des classes CSS spécifiques (couleurs).
        - Formate la date et le prix pour une meilleure lisibilité.
        - Affiche un message "État vide" si aucune commande n'est trouvée.
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

$stmt = $commande->getHistoriqueCommandes($client_id);

include 'views/historique_commandes.php';
?>