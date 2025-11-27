<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    if (!isset($_SESSION['client_id'])) {
        header("Location: login.php");
        exit();
    }

    $commande_id = $_POST['commande_id'];
    $client_id = $_SESSION['client_id'];

    $database = new Database();
    $db = $database->getConnection();
    $commande = new Commande($db);

    // Vérifier que la commande appartient bien au client
    $query = "SELECT client_id FROM commandes WHERE commande_id = :commande_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':commande_id', $commande_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['client_id'] == $client_id) {
        if ($commande->marquerCommeRecue($commande_id)) {
            // Si c'est un invité, vérifier s'il a d'autres commandes en cours avant suppression
            if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true) {
                // Vérifier s'il reste des commandes en cours de livraison
                $query_check = "SELECT COUNT(*) as nb_commandes FROM commandes 
                                WHERE client_id = :client_id 
                                AND etat = 'en_livraison'";
                $stmt_check = $db->prepare($query_check);
                $stmt_check->bindParam(':client_id', $client_id);
                $stmt_check->execute();
                $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

                // Supprimer le compte seulement s'il n'y a plus de commandes en cours
                if ($result_check['nb_commandes'] == 0) {
                    $query_delete = "DELETE FROM clients WHERE client_id = :client_id AND email LIKE 'invite_%@temp.local'";
                    $stmt_delete = $db->prepare($query_delete);
                    $stmt_delete->bindParam(':client_id', $client_id);
                    $stmt_delete->execute();

                    // Détruire la session
                    session_destroy();
                    header("Location: index.php");
                    exit();
                } else {
                    // Il reste des commandes en cours, rediriger vers le suivi
                    header("Location: suivi.php");
                    exit();
                }
            } else {
                header("Location: suivi.php");
                exit();
            }
        }
    }
}

header("Location: suivi.php");
exit();
?>