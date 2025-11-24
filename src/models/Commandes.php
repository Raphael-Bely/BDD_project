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
    
    public function afficherItemCommande($commande_id) {
        $path = __DIR__ . "/../../sql_requests/getAllItemCommande.sql";

        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : impossible de lire fichier de requête SQL :" . $path);
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        
        return $stmt;
    }


    public function createCommandeOrGetLastOne($client_id, $restaurant_id) {
        $path1 = __DIR__ . '/../../sql_requests/getCurrentCommandeFromRestaurant.sql';
        $query1 = file_get_contents($path1);

        if ($query1 == false) die("Erreur SQL:" . $path1);

        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(1, $client_id);
        $stmt1->bindParam(2, $restaurant_id);
        $stmt1->execute();

        $row = $stmt1->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row; 
        }

        // Si on arrive ici c'est que la commande n'existait pas 

        $path2 = __DIR__ . '/../../sql_requests/createNewCommande.sql';
        $query2 = file_get_contents($path2);
        if ($query2 == false) die("Erreur SQL:" . $path2);

        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(1, $client_id);
        $stmt2->bindParam(2, $restaurant_id);
        $stmt2->execute();

        $stmt1->execute();
        
        return $stmt1->fetch(PDO::FETCH_ASSOC);
    }

    public function addItemToCommandeContenirItem($commande_id, $item_id) {
        $path0 = __DIR__ . '/../../sql_requests/getItemInContenirItem.sql';
        $query0 = file_get_contents($path0);
        
        if ($query0 === false) die("Erreur SQL: " . $path0);

        $stmt0 = $this->conn->prepare($query0);
        $stmt0->bindParam(1, $commande_id);
        $stmt0->bindParam(2, $item_id);
        
        if (!$stmt0->execute()) return false;

        if ($stmt0->fetch() === false) {
            
            $path1 = __DIR__ . '/../../sql_requests/addItemToCommandeContenirItem.sql';
            $query1 = file_get_contents($path1);
            
            if ($query1 === false) die("Erreur SQL: " . $path1);

            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(1, $commande_id);
            $stmt1->bindParam(2, $item_id);

            return $stmt1->execute();

        } else {
            
            $path2 = __DIR__ . '/../../sql_requests/updateQuantityContenirItem.sql';
            $query2 = file_get_contents($path2);

            if ($query2 === false) die("Erreur SQL: " . $path2);

            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(1, $commande_id);
            $stmt2->bindParam(2, $item_id);
        
            return $stmt2->execute();    

        }
    }
}
?>