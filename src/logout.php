<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Si c'est un invité, supprimer le compte temporaire
if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true && isset($_SESSION['client_id'])) {
    require_once './config/Database.php';

    $database = new Database();
    $db = $database->getConnection();

    $client_id = $_SESSION['client_id'];

    // Supprimer le client invité
    $query = "DELETE FROM clients WHERE client_id = :client_id AND email LIKE 'invite_%@temp.local'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':client_id', $client_id);
    $stmt->execute();
}

$_SESSION = array();


//vider les cookies (miam)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: index.php");
exit();
?>