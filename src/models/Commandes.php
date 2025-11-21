<?php

class Commande {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCurrentCommande($client_id) {
        $path = __DIR__ . '/../../sql_requests/getCurrentCommande.sql';

        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : impossible de lire fichier de requête SQL getCurrentCommande.sql");
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);

        $stmt->execute();
        
        return $stmt;
    }

    public function suppressCurrentCommande($commande_id) {
        $path = __DIR__ . '/../../sql_requests/suppressCurrentCommande.sql';

        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : impossible de lire fichier de requête SQL :" . $path);
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $commande_id);

        $stmt->execute();
        
        return $stmt;
    }
}

?>