<?php

require_once 'Query.php';

class Fidelite
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * ajoute des points de fidélité à un client pour un restaurant donné.
     * si le compte n'existe pas, il est créé.
     * règle actuelle : 1€ = 1 point.
     */
    public function ajouterPoints($client_id, $restaurant_id, $montant_commande)
    {
        //on arrondit le montant à l'entier inférieur
        $points_a_ajouter = floor($montant_commande);

        if ($points_a_ajouter <= 0) {
            return false;
        }
        //on vérifie si le client a déjà un compte fidélité dans ce restaurant
        $queryCheck = Query::loadQuery('sql_requests/checkFidelite.sql');
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(1, $client_id);
        $stmtCheck->bindParam(2, $restaurant_id);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            //le compte existe -> On met à jour les points
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fidelite_id = $row['fidelite_id'];

            $queryUpdate = Query::loadQuery('sql_requests/updateFidelite.sql');
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(1, $points_a_ajouter);
            $stmtUpdate->bindParam(2, $fidelite_id);
            
            return $stmtUpdate->execute();

        } else {
            //le compte n'existe pas -> On le crée avec les points initiaux
            $queryCreate = Query::loadQuery('sql_requests/createFidelite.sql');
            $stmtCreate = $this->conn->prepare($queryCreate);
            $stmtCreate->bindParam(1, $client_id);
            $stmtCreate->bindParam(2, $restaurant_id);
            $stmtCreate->bindParam(3, $points_a_ajouter);
            
            return $stmtCreate->execute();
        }
    }

    /**
     * récupère le solde de points d'un client pour un restaurant
     */
    public function getSolde($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/checkFidelite.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['points'];
        }
        return 0;
    }
}
?>