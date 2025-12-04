<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Restaurants.php';
require_once './models/Plats.php'; // Pour l'ajout de plats
require_once './models/Formules.php';

// 1. SÉCURITÉ : Vérification de l'accès
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login_restaurateur.php");
    exit();
}

$db = (new Database())->getConnection();
$restaurant = new Restaurant($db);
$item = new Plat($db);
$formule = new Formule($db);
$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_nom = $_SESSION['restaurant_nom'];

// 2. GESTION DES ACTIONS (Router interne)
$page = isset($_GET['page']) ? $_GET['page'] : 'stats'; // Page par défaut
$message_succes = null;
$message_erreur = null;

// --- TRAITEMENT FORMULAIRE : AJOUTER UN PLAT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
    
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $cat_id = intval($_POST['categorie_id']);
    
    // Gestion de la disponibilité (Checkbox)
    $dispo = isset($_POST['disponible']) ? 'TRUE' : 'FALSE';

    if (!empty($nom) && $prix > 0) {
        
        
        if ($item->addItem($nom, $prix, $dispo, $restaurant_id, $cat_id)) {
            $message_succes = "Le plat \"$nom\" a été ajouté à la carte !";
        } else {
            $message_erreur = "Erreur lors de l'ajout du plat.";
        }
    } else {
        $message_erreur = "Veuillez remplir tous les champs correctement.";
    }
}

// --- TRAITEMENT : AJOUTER UNE FORMULE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_formule') {
    
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $cats_ids = isset($_POST['categories']) ? $_POST['categories'] : [];
    
    $conditions_data = [];
    if (isset($_POST['cond_jour']) && is_array($_POST['cond_jour'])) {
        for ($i = 0; $i < count($_POST['cond_jour']); $i++) {
            // On ne garde que les lignes complètes
            if (!empty($_POST['cond_debut'][$i]) && !empty($_POST['cond_fin'][$i])) {
                $conditions_data[] = [
                    'jour'  => intval($_POST['cond_jour'][$i]),
                    'debut' => $_POST['cond_debut'][$i],
                    'fin'   => $_POST['cond_fin'][$i]
                ];
            }
        }
    }

    if (!empty($nom) && $prix > 0 && count($cats_ids) > 0) {
        
        // Appel de la nouvelle méthode
        if ($formule->createFormule($nom, $prix, $restaurant_id, $cats_ids, $conditions_data)) {
            $message_succes = "Formule créée avec succès !";
        } else {
            $message_erreur = "Erreur création.";
        }
    } else {
        $message_erreur = "Veuillez remplir le nom, le prix et la composition.";
    }
}

// --- TRAITEMENT : AJOUTER UN HORAIRE ---
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

// --- TRAITEMENT : SUPPRIMER UN HORAIRE ---
if (isset($_GET['action']) && $_GET['action'] === 'del_horaire' && isset($_GET['id'])) {
    if ($restaurant->deleteHoraire($restaurant_id, $_GET['id'])) {
        $message_succes = "Créneau supprimé.";
    } else {
        $message_erreur = "Impossible de supprimer ce créneau.";
    }
    header("Location: espace_restaurateur.php?page=horaires"); 
    exit(); 
}


// --- PRÉPARATION DES DONNÉES POUR LA VUE ---

// A. Si on est sur la page STATISTIQUES
$stats = [];
if ($page === 'stats') {
    // Appel de la méthode optimisée qu'on a vue ensemble
    $stats = $restaurant->getStats($restaurant_id);
}

// B. Si on est sur la page AJOUT PLAT, on a besoin des catégories
$categories_items = [];
if ($page === 'add_item') {
    $stmt_cat = $item->getItemFromAllCat();
    $categories_items = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
}

// C. Si on est sur la page FORMULES, on a besoin de la liste des catégories pour les cases à cocher
if ($page === 'formules') {
    // On réutilise la méthode du modèle Item pour avoir toutes les catégories dispos
    $stmt_cat = $item->getItemFromAllCat();
    $categories_items = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
    $jours = [1=>'Lundi', 2=>'Mardi', 3=>'Mercredi', 4=>'Jeudi', 5=>'Vendredi', 6=>'Samedi', 7=>'Dimanche'];
    $conditions_dispo = $formule->getAllConditions();
}

// D. Si on est sur la page HORAIRES on a besoin des horaires du resto

$liste_horaires = [];
if ($page === 'horaires') {
    $liste_horaires = $restaurant->getHoraires($restaurant_id);
    
    // Petite astuce pour afficher les noms des jours
    $jours_semaine = [
        1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 
        5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'
    ];
}

// 3. CHARGEMENT DE LA VUE
include 'views/dashboard_restaurateur.php';
?>