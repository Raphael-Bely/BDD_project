<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();


require_once './config/Database.php';
require_once './models/Commandes.php';

// Exiger une session active, ignorer tout paramètre GET
if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
} else {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);

$stmt = $commande->getCurrentCommande($client_id);

$stmt_items = null;

// On vérifie si on a trouvé une commande
// ... (le début de votre fichier reste pareil)

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_cmd = $row['commande_id'];

        // --- 1. Gestion des Articles ---
        $stmt_items = $commande->afficherItemCommande($id_cmd);
        $row['liste_articles'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // --- 2. Gestion des Formules ---
        $stmt_formules = $commande->afficherFormulesCommande($id_cmd);
        $raw_formules = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);

        // INITIALISATION OBLIGATOIRE (Même si vide)
        $formules_structurees = [];

        // On ne rentre ici que s'il y a des résultats
        if (!empty($raw_formules)) {
            foreach ($raw_formules as $ligne) {

                // BLINDAGE ICI : On cherche 'instance_id', sinon on prend 'id', sinon 0
                // Cela empêche l'erreur "Undefined index" même si le SQL est vieux
                $id_unique = $ligne['instance_id'] ?? $ligne['id'] ?? 0;
                $nom_form = $ligne['nom_formule'] ?? $ligne['nom'] ?? 'Nom Inconnu';
                $prix_form = $ligne['prix'] ?? 0;
                $nom_item = $ligne['nom_item'] ?? $ligne['nom'] ?? ''; // Attention collision de noms possible

                // Si l'ID est invalide (0), on saute cette ligne
                if ($id_unique === 0)
                    continue;

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