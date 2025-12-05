<?php

require_once 'Query.php';

class Client
{
    private $conn;


    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get client ID from credentials.
    public function getIdByLogin($nom, $email)
    {
        $query = Query::loadQuery('sql_requests/getClientId.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $email);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Create new client account.
    public function createClient($nom, $email, $telephone, $adresse)
    {
        $query = Query::loadQuery('sql_requests/createClient.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $telephone);
        $stmt->bindParam(4, $adresse);

        $stmt->execute();
        return;
    }

    // Check if email already exists.
    public function newClientEmailAlreadyExist($email)
    {
        $query = Query::loadQuery('sql_requests/newClientEmailAlreadyExist.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    // Delete temporary guest account.
    public function deleteGuestAccount($client_id)
    {
        $query = Query::loadQuery('sql_requests/deleteGuestAccount.sql');

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$client_id]);
    }
}

?>