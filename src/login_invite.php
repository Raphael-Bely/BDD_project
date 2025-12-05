<?php
// src/login_invite.php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';

$error_message = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';

    if (!empty($adresse) && !empty($telephone)) {
        $database = new Database();
        $db = $database->getConnection();

        // Créer un client temporaire (invité)
        $nom_invite = "Invité " . date('YmdHis'); // Nom unique basé sur timestamp
        $email_invite = "invite_" . uniqid() . "@temp.local"; // Email unique temporaire

        $query = "INSERT INTO clients (nom, email, telephone, adresse) VALUES (:nom, :email, :telephone, :adresse) RETURNING client_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom', $nom_invite);
        $stmt->bindParam(':email', $email_invite);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':adresse', $adresse);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Marquer la session comme invité
            $_SESSION['client_id'] = $result['client_id'];
            $_SESSION['client_nom'] = "Invité";
            $_SESSION['is_guest'] = true; // Flag pour indiquer que c'est un invité
            $_SESSION['guest_created_at'] = time(); // Timestamp de création

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Erreur lors de la création du compte invité.";
        }
    } else {
        $error_message = "Veuillez saisir votre adresse de livraison et votre téléphone.";
    }
}

include 'views/guest_login.php';
?>