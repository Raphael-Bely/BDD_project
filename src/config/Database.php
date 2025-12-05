<?php

date_default_timezone_set('Europe/Paris');

// Configuration file for connecting to the database

class Database {

    private $host = "tabeille001.zzz.bordeaux-inp.fr";    
    private $db_name = "tabeille001"; 
    private $username = "tabeille001"; 
    private $password = "bEe1974Ca!"; 
    private $conn;

    // Method for obtaining connection to the database
    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Configure PDO to raise exceptions in case of errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
            die(); // Stop execution if connection fails
        }
        return $this->conn;
    }
}
?>