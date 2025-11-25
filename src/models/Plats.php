<?php

require_once 'Query.php';

class Plat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMenuByRestaurant($restaurant_id) {
        $query = Query::loadQuery('sql_requests/getMenuByRestaurant.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id); // Remplace le premier ? par $restaurant_id dans la requête SQL
        $stmt->execute();

        return $stmt;
    }

    public function getItemsDisponibles($restaurant_id, $categorie_id) {
        $query = Query::loadQuery('sql_requests/getItemFromSpecificCategories.sql');
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id, $categorie_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>