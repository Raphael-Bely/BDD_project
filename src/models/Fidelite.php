<?php

require_once 'Query.php';

class Fidelite
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add or update loyalty points for a client at a restaurant.
    public function ajouterPoints($client_id, $restaurant_id, $montant_commande)
    {
        $points_a_ajouter = floor($montant_commande);
        if ($points_a_ajouter <= 0) {
            return false;
        }

        // Verify if loyalty account exists
        $queryCheck = Query::loadQuery('sql_requests/checkFidelity.sql');
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(1, $client_id);
        $stmtCheck->bindParam(2, $restaurant_id);
        $stmtCheck->execute();

        // if yes then update number of loyalty points
        if ($stmtCheck->rowCount() > 0) {
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fidelite_id = $row['fidelite_id'];
            $queryUpdate = Query::loadQuery('sql_requests/updateFidelity.sql');
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(1, $points_a_ajouter);
            $stmtUpdate->bindParam(2, $fidelite_id);
            return $stmtUpdate->execute();
        } 
        // if no then create the loyalty account with the right 
        else {
            $queryCreate = Query::loadQuery('sql_requests/createFidelity.sql');
            $stmtCreate = $this->conn->prepare($queryCreate);
            $stmtCreate->bindParam(1, $client_id);
            $stmtCreate->bindParam(2, $restaurant_id);
            $stmtCreate->bindParam(3, $points_a_ajouter);
            return $stmtCreate->execute();
        }
    }

    // Get current loyalty points balance.
    public function getSolde($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/checkFidelity.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['points'];
        } else {
            return 0;
        }
    }

    // Get available rewards based on points balance.
    public function getRemisesDisponibles($restaurant_id, $solde_points)
    {        
        $query = Query::loadQuery('sql_requests/getRemisesDisponibles.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':restaurant_id', $restaurant_id);
        $stmt->bindParam(':solde_points', $solde_points);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deduct points when a reward is used.
    public function utiliserPoints($client_id, $restaurant_id, $cout_points)
    {
        $cout = intval($cout_points);
        if ($cout <= 0) return true;

        $sql = "UPDATE fidelite SET points = points - ? WHERE client_id = ? AND restaurant_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $cout, PDO::PARAM_INT);
        $stmt->bindParam(2, $client_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $restaurant_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Add a review and create loyalty record if missing.
    public function ajouterAvis($client_id, $restaurant_id, $note, $contenu)
    {
        try {
            $this->conn->beginTransaction();
            
            // Verify if loyalty account exists
            $queryCheck = Query::loadQuery('sql_requests/checkFidelity.sql');
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(1, $client_id);
            $stmtCheck->bindParam(2, $restaurant_id);
            $stmtCheck->execute();

            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fid_id = null;

            if ($row) {
                
                $fid_id = $row['fidelite_id'];
            

                if (!$fid_id) {
                    throw new Exception("Impossible de récupérer l'ID fidélité.");
                }

                $queryCommentaire = Query::loadQuery('sql_requests/addComment.sql');
                $stmt_com = $this->conn->prepare($queryCommentaire);
                $stmt_com->execute([$contenu, $note, $fid_id]);

                $this->conn->commit();
                return true;
            }
            else {
                return false;
            }

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Retrieve all reviews for a specific restaurant.
    public function getAvisByRestaurant($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCommentsByRestaurant.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>