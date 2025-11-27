<?php
require_once 'config/Database.php';
require_once 'Query.php';

class Restaurant {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRestaurants() {

        $query = Query::loadQuery('sql_requests/getAllRestaurants.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt; 
    }

    public function getByID($restaurant_id) {
        $query = Query::loadQuery('sql_requests/getRestaurantById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$restaurant_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function getFormules($restaurant_id) {
        $query = Query::loadQuery('sql_requests/getFormulesByRestaurantId.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$restaurant_id);
        $stmt->execute();

        return $stmt;
    }

    public function getRestaurantsAround($lat, $lon, $rayon_km) {
        $query = Query::loadQuery('sql_requests/getRestaurantAround.sql');
        $stmt = $this->conn->prepare($query);

        $rayon_m = $rayon_km * 1000;

        $stmt->bindParam(1, $lon);
        $stmt->bindParam(2, $lat);
        $stmt->bindParam(3, $lon);
        $stmt->bindParam(4, $lat);
        $stmt->bindParam(5, $rayon_m);

        $stmt->execute();
        return $stmt;
    }

    public function getAllCategories() {
        $query = Query::loadQuery('sql_requests/getAllCategories.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>