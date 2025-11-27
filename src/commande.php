<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();


require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Fidelite.php';

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
}
else {
    die("Erreur : Aucun ID client fourni.");
}

$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);
$fidelite = new Fidelite($db);
$stmt = $commande->getCurrentCommande($client_id);

$stmt_items = null;


if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_cmd = $row['commande_id'];
        
        // --- AJOUT FIDÉLITÉ ---
        $row['solde_points_actuel'] = $fidelite->getSolde($client_id, $row['restaurant_id']);
        $row['points_gagnes_commande'] = floor($row['prix_total_remise']);

        // --- 1. Gestion des Articles ---
        $stmt_items = $commande->afficherItemCommande($id_cmd);
        $row['liste_articles'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
        $restaurant_commande = $row['nom_restaurant'];

        // --- 2. Gestion des Formules ---
        $stmt_formules = $commande->afficherFormulesCommande($id_cmd);
        $raw_formules = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);
        
        // INITIALISATION OBLIGATOIRE (Même si vide)
        $formules_structurees = []; 
        
        // On ne rentre ici que s'il y a des résultats
        if (!empty($raw_formules)) {
            foreach($raw_formules as $ligne) {
                
                // BLINDAGE ICI : On cherche 'instance_id', sinon on prend 'id', sinon 0
                // Cela empêche l'erreur "Undefined index" même si le SQL est vieux
                $id_unique = $ligne['instance_id'] ?? $ligne['id'] ?? 0;
                $nom_form  = $ligne['nom_formule'] ?? $ligne['nom'] ?? 'Nom Inconnu';
                $prix_form = $ligne['prix'] ?? 0;
                $nom_item  = $ligne['nom_item'] ?? $ligne['nom'] ?? ''; // Attention collision de noms possible

                // Si l'ID est invalide (0), on saute cette ligne
                if ($id_unique === 0) continue;

                if (!isset($formules_structurees[$id_unique])) {
                    $formules_structurees[$id_unique] = [
                        'nom' => $nom_form,
                        'prix' => $prix_form,
                        'items' => [] 
                    ];
                }
                
                // On ajoute l'item seulement s'il a un nom
                if (!empty($nom_item)) {
                    $formules_structurees[$id_unique]['items'][] = $nom_item;
                }
            }
        }
        
        // ASSIGNATION SYSTÉMATIQUE (Vide ou rempli)
        // Cela empêche l'erreur dans la VUE (derniere_commande.php)
        $row['liste_formules'] = $formules_structurees;
        
        $historiqueCommandes[] = $row;
    }
}


include 'views/derniere_commande.php';
?>