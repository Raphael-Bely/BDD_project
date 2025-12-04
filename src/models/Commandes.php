<?php

require_once 'Query.php';

class Commande
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCurrentCommande($client_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrder.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);

        $stmt->execute();

        return $stmt;
    }

    public function suppressCurrentCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/deleteCurrentOrder.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $commande_id);

        $stmt->execute();

        return $stmt;
    }

    public function afficherItemCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllItemsInOrder.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();

        return $stmt;
    }

    public function afficherFormulesCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllFormulasInOrder.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();

        return $stmt;
    }

    public function ajouterAuPanier($client_id, $resto_id, $item_id)
    {
        $query = "SELECT ajouter_au_panier(?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $item_id);

        //renvoie true si la requête réussie
        return $stmt->execute();
    }

    public function addFullFormuleToOrder($client_id, $resto_id, $formule_id, $items_ids)
    {
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

    public function confirmOrder($commande_id)
    {
        $query = Query::loadQuery('sql_requests/confirmOrder.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);

        return $stmt->execute();
    }

    public function getHistoriqueCommandes($client_id)
    {
        $query = Query::loadQuery('sql_requests/getOrderHistory.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();

        return $stmt;
    }

    public function getCommandesEnCours($client_id)
    {
        $query = Query::loadQuery('sql_requests/getOngoingOrders.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();

        return $stmt;
    }

    public function marquerCommeRecue($commande_id)
    {
        $query = "UPDATE commandes SET etat = 'acheve' WHERE commande_id = :commande_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commande_id', $commande_id);

        return $stmt->execute();
    }

    public function getCommandeDetails($commande_id, $client_id)
    {
        $query = Query::loadQuery('sql_requests/getOrderDetails.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->bindParam(2, $client_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isOrderOwnedByClient($commande_id, $client_id)
    {
        $query = Query::loadQuery('sql_requests/getClientWithOrder.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$commande_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result && $result['client_id'] == $client_id);
    }

    public function countOrdersEnCours($client_id)
    {
        // On considère qu'une commande est en cours si elle n'est ni 'reçue' ni 'annulée'
        $query = Query::loadQuery('sql_requests/countOngoingOrders.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$client_id]);
        return $stmt->fetchColumn();
    }
} ?>