<?php

class Database {

    private $host = "tabeille001.zzz.bordeaux-inp.fr";    
    private $db_name = "tabeille001"; 
    private $username = "tabeille001"; 
    private $password = "bEe1974Ca!"; 
    private $conn;

    // Méthode pour obtenir la connexion à la BDD
    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Configure PDO pour qu'il lève des exceptions en cas d'erreur
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
            die(); // Arrête l'exécution si la connexion échoue
        }
        return $this->conn;
    }
}
?>