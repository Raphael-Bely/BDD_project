<?php

require_once 'Query.php';

class Commande {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCurrentCommande($client_id) {
        $query = Query::loadQuery('sql_requests/getCurrentCommande.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);

        $stmt->execute();
        
        return $stmt;
    }

    public function suppressCurrentCommande($commande_id) {
        $query = Query::loadQuery('sql_requests/suppressCurrentCommande.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $commande_id);

        $stmt->execute();
        
        return $stmt;
    }
    
    public function afficherItemCommande($commande_id) {
        $query = Query::loadQuery('sql_requests/getAllItemCommande.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function afficherFormulesCommande($commande_id) {
        $query = Query::loadQuery('sql_requests/getAllFormulesCommandes.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function ajouterAuPanier($client_id, $resto_id, $item_id) {
        $query = "SELECT ajouter_au_panier(?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $item_id);
        
        //renvoie true si la requête réussie
        return $stmt->execute();
    }

    public function addFullFormuleToOrder($client_id, $resto_id, $formule_id, $items_ids) {
        // transforme tableau PHP [,] en format Postgres {,}
        $pg_array = "{" . implode(',', $items_ids) . "}";

        $query = "SELECT ajouter_formule_complete(?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $formule_id);
        $stmt->bindParam(4, $pg_array); 
        
        return $stmt->execute();
    }
}
?>