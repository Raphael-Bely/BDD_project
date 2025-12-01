<?php
require_once 'config/Database.php';
require_once __DIR__ . '/Query.php';

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

    public function getCurrentCommandFromRestaurant($client_id,$restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentCommandFromRestaurant.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

        $stmt->execute();

        return $stmt;
    }

    public function getAllCategories() {
        $query = Query::loadQuery('sql_requests/getAllCategories.sql');
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        return $stmt;
    }

    public function getCategoriesById($cat_id) {
        $query = Query::loadQuery('sql_requests/getCategorieById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$cat_id);

        $stmt->execute();
        return $stmt;
    }
    
    public function getByCategory($category_id) {
        $query = Query::loadQuery('sql_requests/getRestaurantsByCategory.sql');
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $category_id);
        
        $stmt->execute();
        return $stmt;
    }

    public function getCurrentCommandeFromRestaurant($client_id, $restaurant_id) {
        $query = Query::loadQuery('sql_requests/getCurrentCommandFromRestaurant.sql');
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

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

    public function login($email, $mot_de_passe) {
        $query = Query::loadQuery('sql_requests/loginRestaurateur.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resto && $mot_de_passe === $resto['mot_de_passe']) { 
            return $resto;
        }
        return false;
    }

    public function getStats($restaurant_id) {
        $query = Query::loadQuery('sql_requests/getRestaurantStats.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>