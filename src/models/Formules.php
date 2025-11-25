<?php
require_once __DIR__ . '/Query.php'; 

class Formule {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getComposition($formule_id) {
        $query = Query::loadQuery('sql_requests/getComposition.sql');
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$formule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>