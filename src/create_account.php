<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Clients.php';


$error_message = NULL;
$error_message_email = NULL;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';

    if (!empty($nom) && !empty($email) && !empty($adresse)) {
        $database = new Database();
        $db = $database->getConnection();
        $client = new Client($db);

        if ($client->newClientEmailAlreadyExist($email) == false) {
            // Sauvegarder l'ancien client_id si invité
            $ancien_client_id = null;
            if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true && isset($_SESSION['client_id'])) {
                $ancien_client_id = $_SESSION['client_id'];
            }

            $client->createClient($nom, $email, $adresse);
            $result = $client->getIdByLogin($nom, $email);

            if ($result) {
                $nouveau_client_id = $result['client_id'];

                // Transférer les commandes de l'invité vers le nouveau compte
                if ($ancien_client_id) {
                    $query = "UPDATE commandes SET client_id = :nouveau_id WHERE client_id = :ancien_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nouveau_id', $nouveau_client_id);
                    $stmt->bindParam(':ancien_id', $ancien_client_id);
                    $stmt->execute();

                    // Supprimer l'ancien compte invité
                    $query_delete = "DELETE FROM clients WHERE client_id = :ancien_id AND email LIKE 'invite_%@temp.local'";
                    $stmt_delete = $db->prepare($query_delete);
                    $stmt_delete->bindParam(':ancien_id', $ancien_client_id);
                    $stmt_delete->execute();
                }

                $_SESSION['client_id'] = $nouveau_client_id;
                $_SESSION['client_nom'] = $nom;
                unset($_SESSION['is_guest']); // Supprimer le flag invité

                header("Location: index.php");
                exit();
            } else {
                $error_message = "Erreur dans la création d'un compte";
            }
        } else {
            $error_message_email = "Cet email est déjà utilisé pour un autre compte.";
        }
    }
}

include 'views/account_creation.php';


?>