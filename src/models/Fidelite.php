<?php

require_once 'Query.php';

class Fidelite
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function ajouterPoints($client_id, $restaurant_id, $montant_commande)
    {
        $points_a_ajouter = floor($montant_commande);
        if ($points_a_ajouter <= 0) {
            return false;
        }

        $queryCheck = Query::loadQuery('sql_requests/checkFidelity.sql');
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(1, $client_id);
        $stmtCheck->bindParam(2, $restaurant_id);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fidelite_id = $row['fidelite_id'];
            $queryUpdate = Query::loadQuery('sql_requests/updateFidelity.sql');
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(1, $points_a_ajouter);
            $stmtUpdate->bindParam(2, $fidelite_id);
            return $stmtUpdate->execute();
        } else {
            $queryCreate = Query::loadQuery('sql_requests/createFidelity.sql');
            $stmtCreate = $this->conn->prepare($queryCreate);
            $stmtCreate->bindParam(1, $client_id);
            $stmtCreate->bindParam(2, $restaurant_id);
            $stmtCreate->bindParam(3, $points_a_ajouter);
            return $stmtCreate->execute();
        }
    }

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

    public function ajouterAvis($client_id, $restaurant_id, $note, $contenu)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Vérifier si le client a déjà une fidélité
            $queryCheck = Query::loadQuery('sql_requests/checkFidelity.sql');
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(1, $client_id);
            $stmtCheck->bindParam(2, $restaurant_id);
            $stmtCheck->execute();

            // On tente de récupérer la ligne
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fid_id = null;

            if ($row) {
                // Si elle existe, on prend l'ID
                $fid_id = $row['fidelite_id'];
            } else {
                // 2. Si pas de fidélité, on la crée (0 points au départ)
                // IMPORTANT : createFidelity.sql DOIT contenir "RETURNING fidelite_id"
                $points = 0;
                $queryCreate = Query::loadQuery('sql_requests/createFidelity.sql');
                $stmtCreate = $this->conn->prepare($queryCreate);
                $stmtCreate->bindParam(1, $client_id);
                $stmtCreate->bindParam(2, $restaurant_id);
                $stmtCreate->bindParam(3, $points);
                $stmtCreate->execute();

                // On récupère l'ID fraîchement créé
                $fid_id = $stmtCreate->fetchColumn();
            }

            if (!$fid_id) {
                throw new Exception("Impossible de récupérer l'ID fidélité.");
            }

            // 3. Insérer le commentaire avec le bon ID
            $queryCommentaire = Query::loadQuery('sql_requests/addComment.sql');
            $stmt_com = $this->conn->prepare($queryCommentaire);
            // L'ordre des paramètres dépend de votre SQL (Contenu, Note, ID)
            $stmt_com->execute([$contenu, $note, $fid_id]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

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