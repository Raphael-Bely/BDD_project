<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Fidelite.php';

if (!isset($_SESSION['client_id']) || !isset($_POST['commande_id'])) {
    header("Location: index.php");
    exit();
}

$commande_id = $_POST['commande_id'];

$restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null;
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : $_SESSION['client_id'];

$retrait_option = isset($_POST['retrait_option']) ? $_POST['retrait_option'] : 'asap';
$heure_retrait_input = isset($_POST['heure_retrait']) ? trim($_POST['heure_retrait']) : '';

$cout_points = isset($_POST['cout_points']) ? intval($_POST['cout_points']) : 0;

if (empty($restaurant_id)) {
    die("Erreur technique : Identifiant du restaurant manquant.");
}

$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;
$accorder_points = !$is_guest;

$database = new Database();
$db = $database->getConnection();
$commandeModel = new Commande($db);

// Fetch restaurant closing time for validation
$sql_closing = "SELECT MAX(ho.heure_fermeture) AS max_closing FROM avoir_horaires_ouverture aho 
    JOIN horaires_ouverture ho ON aho.horaire_ouverture_id = ho.horaire_ouverture_id 
    WHERE aho.restaurant_id = :resto_id";
$stmt_closing = $db->prepare($sql_closing);
$stmt_closing->bindParam(':resto_id', $restaurant_id, PDO::PARAM_INT);
$stmt_closing->execute();
$closing_row = $stmt_closing->fetch(PDO::FETCH_ASSOC);
$restaurant_closing_time = $closing_row['max_closing'] ?? null;

// Validation de l'heure de livraison : dès que possible ou dans les 5 prochaines heures
$now = new DateTime();
$min = (clone $now)->modify('+30 minutes');
$max = (clone $now)->modify('+5 hours');

// If restaurant closing time is available, use it as the max instead of +5 hours
if ($restaurant_closing_time) {
    $closingTime = new DateTime($now->format('Y-m-d') . ' ' . $restaurant_closing_time);
    // If closing time is today and before now, it's for tomorrow
    if ($closingTime < $now) {
        $closingTime->modify('+1 day');
    }
    if ($closingTime < $max) {
        $max = $closingTime;
    }
}

if ($retrait_option === 'asap') {
    // Don't override database default: pass NULL
    $heure_retrait_db = null;
} else {
    // Parse time as HH:MM and build with correct date
    if (!preg_match('/^(\d{1,2}):(\d{2})$/', $heure_retrait_input, $matches)) {
        header("Location: commande.php?error=heure_invalide");
        exit();
    }

    // Try today first
    $heure_retrait_finale = new DateTime($now->format('Y-m-d') . ' ' . $heure_retrait_input . ':00');

    // If the time is before now+30min or after max, it might be tomorrow
    if ($heure_retrait_finale < $min) {
        // Try tomorrow
        $heure_retrait_finale = new DateTime((clone $now)->modify('+1 day')->format('Y-m-d') . ' ' . $heure_retrait_input . ':00');
    }

    // Final validation: must be between now+30min and restaurant closing time
    if ($heure_retrait_finale < $min || $heure_retrait_finale > $max) {
        header("Location: commande.php?error=heure_hors_plage");
        exit();
    }

    $heure_retrait_db = $heure_retrait_finale->format('Y-m-d H:i:s');
}


$succes = $commandeModel->confirmOrder(
    $commande_id,
    $client_id,
    $restaurant_id,
    $total,
    $heure_retrait_db,
    $retrait_option === 'asap',
    $accorder_points
);

if ($succes) {
    if ($cout_points > 0) {
        $fidelite = new Fidelite($db);
        $fidelite->utiliserPoints($client_id, $restaurant_id, $cout_points);
    }

    header("Location: commande.php?success=1");
    exit();
} else {
    echo "Erreur lors de la confirmation de la commande.";
}
?>