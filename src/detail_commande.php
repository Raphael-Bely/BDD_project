<?php
/*
Résumé :
    - Vérification de l'authentification : Vérifie si l'utilisateur est connecté (via la session). Redirige vers la page de connexion si ce n'est pas le cas.
    - Validation des entrées : S'assure que le paramètre 'id' (ID de la commande) est présent et qu'il est numérique.
    - Sécurité / Autorisation : Vérifie que la commande demandée appartient bien au client connecté en utilisant la méthode `isOrderOwnedByClient`. L'accès est refusé et le script arrêté si la vérification échoue.
    - Récupération des données (Infos principales) : Récupère les détails généraux de la commande (date, total, infos restaurant) via le modèle `Commande`.
    - Récupération des données (Articles) : Récupère tous les articles individuels associés à la commande.
    - Récupération des données (Formules) : Récupère tous les composants de formules associés à la commande.
    - Structuration des données : Traite les données brutes des formules (qui arrivent sous forme d'une ligne par item) pour les transformer en un tableau structuré où les items sont regroupés sous leur instance de formule parente.
    - Rendu de la vue : Inclut le fichier de vue pour afficher les détails de la commande formatés.
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


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erreur : ID de commande manquant ou invalide.");
}

$client_id = $_SESSION['client_id'];
$commande_id = $_GET['id'];

$database = new Database();
$db = $database->getConnection();

$commandeModel = new Commande($db);

if (!$commandeModel->isOrderOwnedByClient($commande_id, $client_id)) {
    die("Accès refusé : Cette commande ne vous appartient pas.");
}

$commande_info = $commandeModel->getCommandeDetails($commande_id, $client_id);

$stmt_items = $commandeModel->afficherItemCommande($commande_id);
$liste_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

$stmt_formules = $commandeModel->afficherFormulesCommande($commande_id);
$raw_formules = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);

$liste_formules = [];
if (!empty($raw_formules)) {
    foreach ($raw_formules as $ligne) {
        $id_unique = $ligne['instance_id'] ?? $ligne['id'] ?? 0;
        if ($id_unique === 0) continue;

        if (!isset($liste_formules[$id_unique])) {
            $liste_formules[$id_unique] = [
                'nom' => $ligne['nom_formule'] ?? 'Menu',
                'prix' => $ligne['prix'] ?? 0,
                'items' => []
            ];
        }
        if (!empty($ligne['nom_item'])) {
            $liste_formules[$id_unique]['items'][] = $ligne['nom_item'];
        }
    }
}

include 'views/detail_commande.php'; 
?>