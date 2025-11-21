<?php
// src/login.php

// 1. Config et inclusions
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Clients.php';

$error_message = null;

// 2. Vérifier si le formulaire a été soumis (Méthode POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Récupération et nettoyage des données
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (!empty($nom) && !empty($email)) {
        
        // Connexion BDD
        $database = new Database();
        $db = $database->getConnection();
        
        // Appel du Modèle
        $clientModel = new Client($db);
        $result = $clientModel->getIdByLogin($nom, $email);

        if ($result) {
            // SUCCÈS : Le client existe
            $id_client = $result['client_id']; // Assurez-vous que la colonne s'appelle bien client_id

            // REDIRECTION vers index.php avec l'ID dans l'URL
            header("Location: index.php?id_client=" . $id_client);
            exit(); // Toujours faire un exit après une redirection header()

        } else {
            // ÉCHEC : Identifiants incorrects
            $error_message = "Nom ou email incorrect.";
        }

    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}

// 3. Si on n'est pas redirigé, on affiche la vue (le formulaire)
include 'views/client_login.php';
?>