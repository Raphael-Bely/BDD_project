<?php

require_once 'Query.php';

class Complements
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Retrieve complements for a specific item.
    public function getComplements($item_id)
    {
        $query = Query::loadQuery('sql_requests/getComplements.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id);
        $stmt->execute();
        return $stmt;
    }

    // Link a complement item to a main item.
    public function addComplement($item_id1, $item_id2)
    {
        $query = Query::loadQuery('sql_requests/addComplement.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id1);
        $stmt->bindParam(2, $item_id2);
        return $stmt->execute();
    }

    // Remove a complement link between items.
    public function deleteComplement($item_id1, $item_id2)
    {
        $query = Query::loadQuery('sql_requests/deleteComplement.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id1);
        $stmt->bindParam(2, $item_id2);
        return $stmt->execute();
    }
}

?>