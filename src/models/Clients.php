<?php

class Client {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIdByLogin($nom, $email) {
        $path = __DIR__ . '/../../sql_requests/getClientId.sql';
        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : Impossible de lire le fichier SQL : ". $path);
        }

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1,$nom);
        $stmt->bindParam(2,$email);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>