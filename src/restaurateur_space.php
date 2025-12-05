<?php
/*
Résumé :
    - Vérification de sécurité : Contrôle si la session contient un 'restaurant_id', sinon redirection vers login.
    - Initialisation des modèles : Instanciation de Restaurant, Plat, Formule, Complements avec la connexion BDD.
    - Gestion des formulaires (POST) :
        - Ajout de plat : Création du plat via le modèle Plat, puis association des compléments via le modèle Complements.
        - Ajout de formule : Création de la formule, liaison des catégories d'items, et création/liaison des conditions horaires via le modèle Formule (Logique transactionnelle).
        - Ajout d'horaire : Création d'un créneau et liaison au restaurant via le modèle Restaurant.
    - Gestion des actions (GET) :
        - Suppression d'horaire : Appel au modèle Restaurant pour supprimer le lien horaire.
    - Préparation des données d'affichage (selon la page demandée) :
        - Stats : Récupération des statistiques de vente agrégées.
        - Ajout Plat/Formules : Récupération de la liste complète des catégories d'items.
        - Horaires : Récupération de la liste des horaires actuels du restaurant.
    - Chargement de la vue : Inclusion du fichier 'views/dashboard_restaurateur.php'.
*/


session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';
require_once './models/Plats.php';
require_once './models/Formules.php';
require_once './models/Complements.php';
require_once './models/Ingredients.php';

if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login_restaurateur.php");
    exit();
}

$db = (new Database())->getConnection();
$restaurant = new Restaurant($db);
$item = new Plat($db);
$formule = new Formule($db);
$ingredientModel = new Ingredient($db);
$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_nom = $_SESSION['restaurant_nom'];

$page = isset($_GET['page']) ? $_GET['page'] : 'stats'; // Page par défaut
$message_succes = null;
$message_erreur = null;

// ajout plat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {

    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $cat_id = intval($_POST['categorie_id']);

    // gestion dispo
    $dispo = isset($_POST['disponible']) ? 'TRUE' : 'FALSE';
    $complements_ids = isset($_POST['complements']) ? $_POST['complements'] : [];

    if (!empty($nom) && $prix > 0) {

        $new_item_id = $item->addItem($nom, $prix, $dispo, $restaurant_id, $cat_id);

        if ($new_item_id) {

            //compléments
            if (!empty($complements_ids)) {
                require_once './models/Complements.php';
                $compModel = new Complements($db);
                foreach ($complements_ids as $comp_id) {
                    $compModel->addComplement($new_item_id, $comp_id);
                }
            }
            $ingredient_noms = isset($_POST['ingredient_nom']) ? $_POST['ingredient_nom'] : [];
            $ingredient_kcal = isset($_POST['ingredient_kcal']) ? $_POST['ingredient_kcal'] : [];
            $ingredient_proteines = isset($_POST['ingredient_proteines']) ? $_POST['ingredient_proteines'] : [];
            $ingredient_quantites = isset($_POST['ingredient_quantite']) ? $_POST['ingredient_quantite'] : [];

            for ($i = 0; $i < count($ingredient_noms); $i++) {
                $nom_ing = trim($ingredient_noms[$i]);
                if ($nom_ing === '') {
                    continue;
                }

                $kcal = isset($ingredient_kcal[$i]) ? max(0, intval($ingredient_kcal[$i])) : 0;
                $prot = isset($ingredient_proteines[$i]) ? max(0, floatval($ingredient_proteines[$i])) : 0;
                $qte = isset($ingredient_quantites[$i]) ? max(1, intval($ingredient_quantites[$i])) : 1;

                $ingredient_id = $ingredientModel->createIngredient($nom_ing, $kcal, $prot);
                $ingredientModel->linkIngredientToItem($new_item_id, $ingredient_id, $qte);
            }

            $message_succes = "✅ Le plat \"$nom\" a été ajouté avec ses compléments et ingrédients !";
        } else {
            $message_erreur = "❌ Erreur lors de l'ajout du plat.";
        }
    }
    $page = 'add_item';
}

// --- TRAITEMENT : METTRE A JOUR UN PLAT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_item') {
    $item_id = intval($_POST['item_id']);
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $cat_id = intval($_POST['categorie_id']);
    $dispo = isset($_POST['disponible']) ? 'TRUE' : 'FALSE';

    if ($item->updateItem($item_id, $restaurant_id, $nom, $prix, $dispo, $cat_id)) {
        $message_succes = "✅ Plat mis à jour.";
    } else {
        $message_erreur = "❌ Impossible de mettre à jour le plat.";
    }
    $page = 'add_item';
}

// --- TRAITEMENT : AJOUTER UN INGREDIENT A UN PLAT EXISTANT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_ingredient_to_item') {
    $item_id = intval($_POST['item_id']);
    $nom_ing = isset($_POST['ingredient_nom']) ? trim($_POST['ingredient_nom']) : '';
    $kcal = isset($_POST['ingredient_kcal']) ? max(0, intval($_POST['ingredient_kcal'])) : 0;
    $prot = isset($_POST['ingredient_proteines']) ? max(0, floatval($_POST['ingredient_proteines'])) : 0;
    $qte = isset($_POST['ingredient_quantite']) ? max(1, intval($_POST['ingredient_quantite'])) : 1;

    if ($item_id > 0 && $nom_ing !== '') {
        $ingredient_id = $ingredientModel->createIngredient($nom_ing, $kcal, $prot);
        $ingredientModel->linkIngredientToItem($item_id, $ingredient_id, $qte);
        $message_succes = "✅ Ingrédient ajouté au plat.";
    } else {
        $message_erreur = "❌ Merci de renseigner un nom d'ingrédient.";
    }
    $page = 'add_item';
}

// --- TRAITEMENT : SUPPRIMER UN INGREDIENT D'UN PLAT EXISTANT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_ingredient_from_item') {
    $item_id = intval($_POST['item_id']);
    $ingredient_id = intval($_POST['ingredient_id']);

    if ($item_id > 0 && $ingredient_id > 0) {
        $ingredientModel->deleteIngredientFromItem($item_id, $ingredient_id);
        $message_succes = "✅ Ingrédient supprimé du plat.";
    } else {
        $message_erreur = "❌ Paramètres invalides pour supprimer l'ingrédient.";
    }
    $page = 'add_item';
}

// --- TRAITEMENT : TOGGLE DISPONIBILITÉ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_disponible') {
    $item_id = intval($_POST['item_id']);
    $result = $item->toggleItemAvailability($item_id, $restaurant_id);
    if ($result) {
        $message_succes = "✅ Disponibilité mise à jour.";
    } else {
        $message_erreur = "❌ Impossible de mettre à jour la disponibilité.";
    }
    $page = 'add_item'; // Stay on the items management page
}

// --- TRAITEMENT : SUPPRIMER UN PLAT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_item') {
    $item_id = intval($_POST['item_id']);
    if ($item->deleteItem($item_id, $restaurant_id)) {
        $message_succes = "✅ Plat supprimé.";
    } else {
        $message_erreur = "❌ Impossible de supprimer ce plat.";
    }
    $page = 'add_item';
}

// ajout formule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_formule') {

    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $cats_ids = isset($_POST['categories']) ? $_POST['categories'] : [];

    $conditions_data = [];
    if (isset($_POST['cond_jour']) && is_array($_POST['cond_jour'])) {
        for ($i = 0; $i < count($_POST['cond_jour']); $i++) {
            if (!empty($_POST['cond_debut'][$i]) && !empty($_POST['cond_fin'][$i])) {
                $conditions_data[] = [
                    'jour' => intval($_POST['cond_jour'][$i]),
                    'debut' => $_POST['cond_debut'][$i],
                    'fin' => $_POST['cond_fin'][$i]
                ];
            }
        }
    }

    if (!empty($nom) && $prix > 0 && count($cats_ids) > 0) {
        if ($formule->createFormule($nom, $prix, $restaurant_id, $cats_ids, $conditions_data)) {
            $message_succes = "✅ Formule créée avec succès !";
        } else {
            $message_erreur = "❌ Erreur création.";
        }
    } else {
        $message_erreur = "❌ Veuillez remplir le nom, le prix et la composition.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_formule') {
    $formule_id = intval($_POST['formule_id']);
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    if ($formule->updateFormule($formule_id, $restaurant_id, $nom, $prix)) {
        $message_succes = "✅ Formule mise à jour.";
    } else {
        $message_erreur = "❌ Impossible de mettre à jour la formule.";
    }
    $page = 'formules';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_formule') {
    $formule_id = intval($_POST['formule_id']);
    if ($formule->deleteFormule($formule_id, $restaurant_id)) {
        $message_succes = "✅ Formule supprimée.";
    } else {
        $message_erreur = "❌ Impossible de supprimer cette formule.";
    }
    $page = 'formules';
}

// ajout horaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_horaire') {
    $jour = intval($_POST['jour']);
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];

    if ($jour >= 1 && $jour <= 7 && !empty($debut) && !empty($fin)) {
        if ($restaurant->addHoraire($restaurant_id, $jour, $debut, $fin)) {
            $message_succes = "Nouveau créneau ajouté.";
        } else {
            $message_erreur = "Erreur lors de l'ajout.";
        }
    }
}

// suppression horaire
if (isset($_GET['action']) && $_GET['action'] === 'del_horaire' && isset($_GET['id'])) {
    if ($restaurant->deleteHoraire($restaurant_id, $_GET['id'])) {
        $message_succes = "Créneau supprimé.";
    } else {
        $message_erreur = "Impossible de supprimer ce créneau.";
    }
    header("Location: restaurateur_space.php?page=horaires");
    exit();
}


// Données pour la vue

// page stats
$stats = [];
if ($page === 'stats') {
    $stats = $restaurant->getStats($restaurant_id);
}

// page ajout de plat
$categories_items = [];
$items_owner = [];
if ($page === 'add_item') {
    $stmt_cat = $item->getItemFromAllCat();
    $categories_items = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
    $items_owner = $item->getItemsForOwner($restaurant_id);
    foreach ($items_owner as &$it) {
        $stmtIng = $ingredientModel->getIngredientsByItem($it['item_id']);
        $it['ingredients'] = $stmtIng->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($it);
}

//page formules
$formules_owner = [];
if ($page === 'formules') {
    $stmt_cat = $item->getItemFromAllCat();
    $categories_items = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
    $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
    $conditions_dispo = $formule->getAllConditions();
    $formules_owner = $formule->getFormulesForOwner($restaurant_id);
}

// page horaires
$liste_horaires = [];
if ($page === 'horaires') {
    $liste_horaires = $restaurant->getHoraires($restaurant_id);

    $jours_semaine = [
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche'
    ];
}

include 'views/dashboard_restaurateur.php';
?>