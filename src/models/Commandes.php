<?php

require_once 'Query.php';
require_once 'Fidelite.php';

class Commande
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Retrieve the current active order for a client.
    public function getCurrentCommande($client_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    // Delete the current order from the database.
    public function suppressCurrentCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/deleteCurrentOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    // Retrieve all individual items associated with an order.
    public function afficherItemCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllItemsInOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    // Retrieve all formulas associated with an order.
    public function afficherFormulesCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllFormulasInOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    // Add an item to the cart using a stored procedure (see function in create.sql).
    public function ajouterAuPanier($client_id, $resto_id, $item_id)
    {
        $query = "SELECT ajouter_au_panier(?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $item_id);
        return $stmt->execute();
    }

    // Add a complete formula with its items using a stored procedure. (see function in create.sql)
    public function addFullFormuleToOrder($client_id, $resto_id, $formule_id, $items_ids)
    {
        $pg_array = "{" . implode(',', $items_ids) . "}";
        $query = "SELECT ajouter_formule_complete(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $formule_id);
        $stmt->bindParam(4, $pg_array);
        return $stmt->execute();
    }

    // Finalize the order, update price, and add loyalty points.
    public function confirmOrder($commande_id, $client_id, $restaurant_id, $prix_total, $heure_retrait, $est_asap = false, $accorder_points = true)
    {
        try {
            $this->conn->beginTransaction();

            if ($heure_retrait === null) {
                $sqlPrice = "UPDATE commandes SET prix_total_remise = ?, est_asap = ? WHERE commande_id = ?";
                $stmtPrice = $this->conn->prepare($sqlPrice);
                $stmtPrice->bindValue(1, $prix_total);
                $stmtPrice->bindValue(2, $est_asap ? 1 : 0);
                $stmtPrice->bindValue(3, $commande_id);
            } else {
                $sqlPrice = "UPDATE commandes SET prix_total_remise = ?, heure_retrait = ?, est_asap = ? WHERE commande_id = ?";
                $stmtPrice = $this->conn->prepare($sqlPrice);
                $stmtPrice->bindValue(1, $prix_total);
                $stmtPrice->bindValue(2, $heure_retrait);
                $stmtPrice->bindValue(3, $est_asap ? 1 : 0);
                $stmtPrice->bindValue(4, $commande_id);
            }
            $stmtPrice->execute();

            $queryConfirm = Query::loadQuery('sql_requests/confirmOrder.sql');
            $stmtConfirm = $this->conn->prepare($queryConfirm);
            $stmtConfirm->bindParam(1, $commande_id);
            $stmtConfirm->execute();

            if ($accorder_points) {
                $fidelite = new Fidelite($this->conn);
                $fidelite->ajouterPoints($client_id, $restaurant_id, $prix_total);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Retrieve the order history for a specific client.
    public function getHistoriqueCommandes($client_id)
    {
        $query = Query::loadQuery('sql_requests/getOrderHistory.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt;
    }

    // Retrieve orders currently in progress.
    public function getCommandesEnCours($client_id)
    {
        $query = Query::loadQuery('sql_requests/getOngoingOrders.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt;
    }

    // Mark the order as completed/received.
    public function marquerCommeRecue($commande_id)
    {
        $query = "UPDATE commandes SET etat = 'acheve' WHERE commande_id = :commande_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commande_id', $commande_id);
        return $stmt->execute();
    }

    // Get detailed information about a specific order.
    public function getCommandeDetails($commande_id, $client_id)
    {
        $query = Query::loadQuery('sql_requests/getOrderDetails.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->bindParam(2, $client_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if the order belongs to the given client.
    public function isOrderOwnedByClient($commande_id, $client_id)
    {
        $query = Query::loadQuery('sql_requests/getClientWithOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$commande_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result && $result['client_id'] == $client_id);
    }

    // Count the number of active orders for a client.
    public function countOrdersEnCours($client_id)
    {
        $query = Query::loadQuery('sql_requests/countOngoingOrders.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$client_id]);
        return $stmt->fetchColumn();
    }
}
?>