<?php
/*
Résumé :
    - Validation des entrées : Vérifie que le champ 'adresse' n'est pas vide après nettoyage.
    - Connexion BDD : Établit la connexion à la base de données.
    - Création de compte temporaire :
        - Génère automatiquement un nom d'utilisateur unique ("Invité [Timestamp]").
        - Génère une adresse email fictive unique ("invite_[ID]@temp.local") pour satisfaire la contrainte d'unicité de la base de données sans demander d'email réel.
    - Insertion SQL : Exécute une requête `INSERT` dans la table `clients` et récupère immédiatement l'ID généré (`RETURNING client_id`).
    - Gestion de Session (Succès) :
        - Stocke l'ID du nouveau client temporaire.
        - Définit un indicateur `is_guest = true` pour différencier ce compte des comptes permanents (utile pour bloquer la fidélité ou forcer le nettoyage plus tard).
        - Enregistre l'heure de création.
        - Redirige vers la page d'accueil (`index.php`) pour commencer la commande.
    - Gestion d'Erreur (Échec) : Définit un message d'erreur et réaffiche le formulaire si l'insertion échoue ou si l'adresse manque.
*/

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

        $nom_invite = "Invité " . date('YmdHis');
        $email_invite = "invite_" . uniqid() . "@temp.local";

        $query = "INSERT INTO clients (nom, email, telephone, adresse) VALUES (:nom, :email, :telephone, :adresse) RETURNING client_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom', $nom_invite);
        $stmt->bindParam(':email', $email_invite);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':adresse', $adresse);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['client_id'] = $result['client_id'];
            $_SESSION['client_nom'] = "Invité";
            $_SESSION['is_guest'] = true;
            $_SESSION['guest_created_at'] = time();

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