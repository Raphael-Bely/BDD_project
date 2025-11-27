<?php

require_once 'Query.php';

class Client {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIdByLogin($nom, $email) {
        $query = Query::loadQuery('sql_requests/getClientId.sql');

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1,$nom);
        $stmt->bindParam(2,$email);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createClient($nom, $email, $adresse) {
        $query = Query::loadQuery('sql_requests/createClient.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $adresse);

        $stmt->execute();
        return;
    }

    public function newClientEmailAlreadyExist($email) {
        $query = Query::loadQuery('sql_requests/newClientEmailAlreadyExist.sql');

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1,$email);

        $stmt->execute();


        return $stmt->fetch() !== false;
    }
}

?>