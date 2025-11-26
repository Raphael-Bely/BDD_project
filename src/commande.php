<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();


require_once './config/Database.php';
require_once './models/Commandes.php';

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
}
else {
    die("Erreur : Aucun ID client fourni.");
}

$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);

$stmt = $commande->getCurrentCommande($client_id);

$commandeInfo = null;
$stmt_items = null;

// On vérifie si on a trouvé une commande
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_cmd = $row['commande_id'];
        
        $stmt_items = $commande->afficherItemCommande($id_cmd);
        $row['liste_articles'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC); // On récupère tous les articles en tableau

        $stmt_formules = $commande->afficherFormulesCommande($id_cmd);
        $raw_formules['liste_formules'] = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);

        // il faut regrouper les lignes avec la formules qui leur correpond :
        $formules_structurees = [];

        if ($raw_formules) {
            foreach($raw_formules as $ligne) {
                $id_unique = $ligne['instance_id'];

                if (!isset($formules_structurees[$id_unique])) {
                    $formules_structurees[$id_unique] = [
                        'nom' => $ligne['nom_formule'],
                        'prix' => $ligne['prix'],
                        'item' => []
                    ];
                }

                $formules_structurees[$id_unique]['item'][] = $ligne['nom_item'];
            }

            $row['liste_formules'] = $formules_structurees;
        }
        
        
        
        $historiqueCommandes[] = $row;
    }
}

include 'views/derniere_commande.php';
?>